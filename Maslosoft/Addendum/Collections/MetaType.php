<?php

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Interfaces\IAnnotationEntity;
use ReflectionClass;

/**
 * Container for class metadata generated by class annotations
 *
 * @author Piotr
 */
class MetaType implements IAnnotationEntity
{

	/**
	 * Class name
	 * @var string
	 */
	public $name = '';

	/**
	 * Class constructor, set some basic metadata
	 * @param ReflectionClass $info
	 */
	public function __construct(ReflectionClass $info = null)
	{
		// For internal use
		if (null === $info)
		{
			return;
		}
		$this->name = $info->name;
	}

	public static function __set_state($data)
	{
		$obj = new self(null);
		foreach ($data as $field => $value)
		{
			$obj->$field = $value;
		}
		return $obj;
	}

	public function __get($name)
	{
		return null;
	}

}