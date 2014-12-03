<?php
namespace Maslosoft\Addendum\Reflection;

use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use ReflectionProperty;
class ReflectionAnnotatedProperty extends ReflectionProperty
{

	/**
	 *
	 * @var AnnotationsCollection
	 */
	private $annotations;

	public function __construct($class, $name)
	{
		parent::__construct($class, $name);
		$this->annotations = $this->createAnnotationBuilder()->build($this);
	}

	public function hasAnnotation($class)
	{
		return $this->annotations->hasAnnotation($class);
	}

	public function getAnnotation($annotation)
	{
		return $this->annotations->getAnnotation($annotation);
	}

	public function getAnnotations()
	{
		return $this->annotations->getAnnotations();
	}

	public function getAllAnnotations($restriction = false)
	{
		return $this->annotations->getAllAnnotations($restriction);
	}

	public function getDeclaringClass()
	{
		$class = parent::getDeclaringClass();
		return new ReflectionAnnotatedClass($class->name);
	}

	/**
	 * Create new annotations builder instance
	 * @return Builder
	 */
	protected function createAnnotationBuilder()
	{
		return new Builder();
	}

}
