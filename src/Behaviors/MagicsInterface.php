<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Behaviors;

use ApiGen\ElementReflection\Magic\MagicMethodReflection;


interface MagicsInterface
{


	/**
	 * Get reflections of annotation defined methods
	 *
	 * @return MagicMethodReflection[]
	 */
	function getMagicMethods();

}
