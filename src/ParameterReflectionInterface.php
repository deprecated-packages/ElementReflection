<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;

use ApiGen\ElementReflection\Behaviors\StartEndLineInterface;


interface ParameterReflectionInterface extends ReflectionInterface, StartEndLineInterface
{

	/**
	 * Returns an element pretty (docblock compatible) name.
	 *
	 * @return string
	 */
	function getPrettyName();


	/**
	 * Returns the declaring class.
	 *
	 * @return ClassReflectionInterface|NULL
	 */
	function getDeclaringClass();


	/**
	 * Returns the declaring function.
	 *
	 * @return ReflectionFunctionBaseInterface
	 */
	function getDeclaringFunction();


	/**
	 * Returns the default value.
	 *
	 * @return mixed
	 */
	function getDefaultValue();


	/**
	 * Returns if the parameter expects an array.
	 *
	 * @return bool
	 */
	function isArray();


	/**
	 * Returns reflection of the required class of the value.
	 *
	 * @return ClassReflectionInterface|null
	 */
	function getClass();


	/**
	 * Returns if the parameter is optional.
	 *
	 * @return bool
	 */
	function isOptional();


	/**
	 * Return if the parameter is variadic.
	 *
	 * @return bool
	 */
	function isVariadic();


	/**
	 * Returns if the parameter value is passed by reference.
	 *
	 * @return bool
	 */
	function isPassedByReference();

}
