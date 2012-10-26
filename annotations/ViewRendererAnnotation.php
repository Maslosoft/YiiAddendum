<?php

/**
 * Use this annotation to set view renderer
 * Renderer must implement IViewRenderer
 * @template ViewRenderer('${RendererClass}')
 * @author Piotr
 */
class ViewRendererAnnotation extends EModelMetaAnnotation
{
	public $value = null;

	public function init()
	{
		if(!isset($this->_entity->renderer))
		{
			$this->_entity->renderer = new stdClass();
		}
		$this->_entity->renderer->view = $this->value;
	}
}