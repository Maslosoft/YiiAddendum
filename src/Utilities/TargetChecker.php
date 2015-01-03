<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Addendum\Interfaces\IAnnotation;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use UnexpectedValueException;

/**
 * Check target constraits
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TargetChecker
{

	/**
	 * Check target constraints
	 * @param IAnnotation $annotation Annotation
	 * @param ReflectionClass|ReflectionMethod|ReflectionProperty|bool $target
	 * @return type
	 * @throws UnexpectedValueException
	 */
	public static function check($annotation, $target)
	{
		$reflection = new ReflectionAnnotatedClass($annotation);
		if (!$reflection->hasAnnotation('Target'))
		{
			return;
		}
		$value = $reflection->getAnnotation('Target')->value;
		$values = is_array($value) ? $value : [$value];
		/**
		 * TODO Add concrete class constraint combined with field. ie.:
		 * Target('Some\Target\ClassName') - Only on this class and subclasses
		 * Target('Some\Target\ClassName', 'property') - Only on this class and subclasses properties
		 * Target('Some\Target\ClassName', 'method') - Only on this class and subclasses methods
		 */
		foreach ($values as $value)
		{
			if ($value == TargetAnnotation::TargetClass && $target instanceof ReflectionClass)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetMethod && $target instanceof ReflectionMethod)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetProperty && $target instanceof ReflectionProperty)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetNested && $target === false)
			{
				return;
			}
		}
		if ($target === false && $value == TargetAnnotation::TargetNested)
		{
			throw new UnexpectedValueException("Annotation '" . get_class($annotation) . "' nesting not allowed");
		}
		elseif (in_array($value, TargetAnnotation::getTargets()))
		{
			throw new UnexpectedValueException(sprintf("Annotation '%s' not allowed on %s, it's target is %s", get_class($annotation), ReflectionName::createName($target), $value));
		}
	}

}