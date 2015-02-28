<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;


interface ClassConstantReflectionInterface extends ConstantReflectionInterface
{

	/**
	 * Returns a reflection of the declaring class.
	 *
	 * @return ClassReflectionInterface
	 */
	function getDeclaringClass();


	/**
	 * Returns the defining trait.
	 *
	 * @return ClassReflectionInterface|NULL
	 */
	function getDeclaringTrait();

}
