<?php

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Interfaces\IAnnotationEntity;
use ReflectionProperty;

/**
 * Container for metadata generated by property annotations
 *
 * @author Piotr
 */
class MetaProperty implements IAnnotationEntity
{

// <editor-fold defaultstate="collapsed" desc="Access Control">
	/**
	 * Indicates if field has getter
	 * @var bool
	 */
	public $callGet = false;

	/**
	 * Indicates if field has setter
	 * @var bool
	 */
	public $callSet = false;

	/**
	 * Indicates if field has either getter or setter
	 * @var bool
	 */
	public $direct = false;

	/**
	 * Getter method name
	 * @var string
	 */
	public $methodGet = '';

	/**
	 * Setter method name
	 * @var string
	 */
	public $methodSet = '';

	/**
	 * True if property is static
	 * @var bool
	 */
	public $isStatic = false;
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Default value and property name">
	/**
	 * Default value of field as defined in class declaration
	 * @var mixed
	 */
	public $default = null;

	/**
	 * Name of a field
	 * @var string
	 */
	public $name = '';

// </editor-fold>

	/**
	 * Class constructor, sets some basic data for field
	 * @param ReflectionProperty $info
	 */
	public function __construct(ReflectionProperty $info = null)
	{
		// For internal use
		if (null === $info)
		{
			return;
		}
		$this->name = $info->name;
		$this->methodGet = 'get' . ucfirst($this->name);
		$this->methodSet = 'set' . ucfirst($this->name);
		$this->isStatic = $info->isStatic();
	}

	public function __get($name)
	{
		return null;
	}

}
