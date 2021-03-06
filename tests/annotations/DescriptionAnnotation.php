<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations;

/**
 * DescriptionAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DescriptionAnnotation extends \Maslosoft\Addendum\Collections\MetaAnnotation
{

	public $value;

	public function init()
	{
		$this->getEntity()->description = (string) $this->value;
	}

}
