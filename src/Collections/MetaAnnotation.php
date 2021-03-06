<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link https://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Annotation;
use Maslosoft\Addendum\Interfaces\AnnotationEntityInterface;
use Maslosoft\Addendum\Interfaces\MetaAnnotationInterface;

/**
 * Annotation used for Collections\Meta
 * @author Piotr
 */
abstract class MetaAnnotation extends Annotation implements MetaAnnotationInterface
{

	/**
	 * Name of annotated field/method/class
	 * @var string
	 */
	public $name = '';

	/**
	 * Model metadata object
	 *
	 * NOTE: Deprecation notice is only to discourage direct use in annotations, this is actually required
	 * @deprecated Use getMeta() instead
	 *
	 * @var Meta
	 */
	private $_meta = null;

	/**
	 * Annotations entity, it can be either class, property, or method
	 * Its concrete annotation implementation responsibility to decide what to do with it.
	 *
	 * NOTE: Deprecation notice is only to discourage direct use in annotations, this is actually required
	 * @deprecated Use getEntity() instead
	 *
	 * @var AnnotationEntityInterface
	 */
	private $_entity = null;

	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Set metadata class to be accessible for annotation for init etc. methods
	 * @param Meta $meta
	 */
	public function setMeta(Meta $meta)
	{
		$this->_meta = $meta;
	}

	/**
	 * Get metadata class for whole entity.
	 *
	 * This allows access to type, method or property in any annotation,
	 * regardless of it's location.
	 * 
	 * @return Meta
	 */
	public function getMeta()
	{
		return $this->_meta;
	}

	/**
	 * Set annotations entity, it can be either class, property, or method
	 * @param AnnotationEntityInterface $entity
	 */
	public function setEntity(AnnotationEntityInterface $entity)
	{
		$this->_entity = $entity;
	}

	/**
	 * Get annotated entity.
	 *
	 * Use this in annotations definitions to define it's params, ie:
	 *
	 * ```php
	 * public function init()
	 * {
	 * 		$this->getEntity()->someValue = $this->value;
	 * }
	 * ```
	 *
	 * @return AnnotationEntityInterface
	 */
	public function getEntity()
	{
		return $this->_entity;
	}

	/**
	 * This function should be called after all annotations are initialized.
	 * Any code that depends on other annotations can be executed here.
	 * NOTE: This is not ensured to run, its annotations container responsibility to call it.
	 * @deprecated since version number 5
	 */
	public function afterInit()
	{
		
	}

}
