<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Builder;

use Exception;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Interfaces\AnnotationInterface;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Addendum\Utilities\Blacklister;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Addendum\Utilities\ReflectionName;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @Label("Annotations builder")
 */
class Builder
{

	/**
	 * Cached values of parsing
	 * @var string[][][]
	 */
	private static $_cache = [];

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	private $addendum = null;

	public function __construct(Addendum $addendum = null)
	{
		$this->addendum = $addendum? : new Addendum();
	}

	/**
	 * Build annotations collection
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $targetReflection
	 * @return AnnotationsCollection
	 */
	public function build($targetReflection)
	{
		$annotations = [];
		$t = [];



		// Decide where from take traits
		if ($targetReflection instanceof ReflectionClass)
		{
			$t = $targetReflection->getTraits();
		}
		else
		{
			$t = $targetReflection->getDeclaringClass()->getTraits();
		}

		// Get annotations from traits
		$traitsData = [];
		foreach ($t as $trait)
		{
			$targetTrait = new ReflectionAnnotatedClass($trait->name, $this->addendum);
			$annotationsTrait = null;

			// Try to get annotations from entity, be it method, property or trait itself
			switch (true)
			{
				case $targetReflection instanceof ReflectionProperty && $targetTrait->hasProperty($targetReflection->name):
					$annotationsTrait = new ReflectionAnnotatedProperty($targetTrait->name, $targetReflection->name, $this->addendum);
					break;
				case $targetReflection instanceof ReflectionMethod && $targetTrait->hasMethod($targetReflection->name):
					$annotationsTrait = new ReflectionAnnotatedMethod($targetTrait->name, $targetReflection->name, $this->addendum);
					break;
				case $targetReflection instanceof \ReflectionClass:
					$annotationsTrait = $targetTrait;
					break;
			}

			// Does not have property or method
			if (null === $annotationsTrait)
			{
				continue;
			}

			// Data from traits
			$traitsData = $this->_parse($annotationsTrait);
		}

		// Data from class
		$data = $this->_parse($targetReflection);

		// Merge data from traits
		$data = array_merge($traitsData, $data);

		// Get annotations from current entity
		foreach ($data as $class => $parameters)
		{
			foreach ($parameters as $params)
			{
				$annotation = $this->instantiateAnnotation($class, $params, $targetReflection);
				if ($annotation !== false)
				{
					$annotations[$class][] = $annotation;
				}
			}
		}
		return new AnnotationsCollection($annotations);
	}

	/**
	 * Create new instance of annotation
	 * @param string $class
	 * @param mixed[] $parameters
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|bool $targetReflection
	 * @return boolean|object
	 */
	public function instantiateAnnotation($class, $parameters, $targetReflection = false)
	{
		$class = ucfirst($class) . "Annotation";

		// If namespaces are empty assume global namespace
		$fqn = $this->_normalizeFqn('\\', $class);
		foreach ($this->addendum->namespaces as $ns)
		{
			$fqn = $this->_normalizeFqn($ns, $class);
			if (Blacklister::ignores($fqn))
			{
				continue;
			}
			try
			{
				if (!ClassChecker::exists($fqn))
				{
					$this->addendum->getLogger()->debug('Annotation class `{fqn}` not found, ignoring', ['fqn' => $fqn]);
					Blacklister::ignore($fqn);
				}
				else
				{
					// Class exists, exit loop
					break;
				}
			}
			catch (Exception $e)
			{
				// Ignore class autoloading errors
			}
		}
		if (Blacklister::ignores($fqn))
		{
			return false;
		}
		try
		{
			// NOTE: @ need to be used here or php might complain
			if (@!class_exists($fqn))
			{
				$this->addendum->getLogger()->debug('Annotation class `{fqn}` not found, ignoring', ['fqn' => $fqn]);
				Blacklister::ignore($fqn);
				return false;
			}
		}
		catch (Exception $e)
		{
			// Ignore autoload errors and return false
			Blacklister::ignore($fqn);
			return false;
		}
		$resolvedClass = Addendum::resolveClassName($fqn);
		if ((new ReflectionClass($resolvedClass))->implementsInterface(AnnotationInterface::class) || $resolvedClass == AnnotationInterface::class)
		{
			return new $resolvedClass($parameters, $targetReflection);
		}
		return false;
	}

	/**
	 * Normalize class name and namespace to proper fully qualified name
	 * @param string $ns
	 * @param string $class
	 * @return string
	 */
	private function _normalizeFqn($ns, $class)
	{
		return preg_replace('~\\\+~', '\\', "\\$ns\\$class");
	}

	/**
	 * Get doc comment
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $reflection
	 * @return mixed[]
	 */
	private function _parse($reflection)
	{
		$key = ReflectionName::createName($reflection);
		if (!isset(self::$_cache[$key]))
		{
			$parser = new AnnotationsMatcher;
			$data = [];
			$parser->setPlugins(new MatcherConfig([
				'addendum' => $this->addendum,
				'reflection' => $reflection
			]));
			$parser->matches($this->getDocComment($reflection), $data);
			self::$_cache[$key] = $data;
		}
		return self::$_cache[$key];
	}

	/**
	 * Get doc comment
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $reflection
	 * @return mixed[]
	 */
	protected function getDocComment($reflection)
	{
		return Addendum::getDocComment($reflection);
	}

	/**
	 * Clear local parsing cache
	 */
	public static function clearCache()
	{
		self::$_cache = [];
	}

}
