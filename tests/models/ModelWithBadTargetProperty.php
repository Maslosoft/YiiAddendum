<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * @Maslosoft\AddendumTest\Annotations\WithTargetProperty
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithBadTargetProperty implements AnnotatedInterface
{

	/**
	 * @var string
	 */
	public $test = '';

}
