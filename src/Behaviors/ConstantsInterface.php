<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Behaviors;

use ApiGen\ElementReflection\ClassConstantReflectionInterface;


interface ConstantsInterface
{

	/**
	 * Returns if the class defines the given constant.
	 *
	 * @param string $name
	 * @return bool
	 */
	function hasConstant($name);


	/**
	 * @param string $name
	 * @return ClassConstantReflectionInterface
	 */
	function getConstant($name);


	/**
	 * Returns an array of constant values.
	 *
	 * @return ClassConstantReflectionInterface[]
	 */
	function getConstants();


	/**
	 * Returns if the class (and not its parents) defines the given constant.
	 *
	 * @param string $name
	 * @return bool
	 */
	function hasOwnConstant($name);


	/**
	 * Returns values of constants declared by this class, not by its parents.
	 *
	 * @return ClassConstantReflectionInterface[]
	 */
	function getOwnConstants();

}
