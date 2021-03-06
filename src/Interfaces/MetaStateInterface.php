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

namespace Maslosoft\Addendum\Interfaces;

use Maslosoft\Addendum\Traits\MetaState;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface MetaStateInterface
{

	/**
	 * This method is called when pulling object from cache.
	 * It is required for all meta (sub) containers to implement this.
	 * There is generic implementation available as trait `MetaState`.
	 * @see MetaState
	 * @param mixed $data
	 */
	public static function __set_state($data);
}
