<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;

use ApiGen\ElementReflection\Behaviors\NamespacesInterface;
use ApiGen\ElementReflection\Behaviors\ParametersInterface;
use ApiGen\ElementReflection\Behaviors\StartEndLineInterface;


interface ReflectionFunctionBaseInterface extends ReflectionInterface, StartEndLineInterface,
	ParametersInterface, NamespacesInterface
{

	/**
	 * Return if the function/method is variadic.
	 *
	 * @return bool
	 */
	function isVariadic();

	/**
	 * Returns if the function/method returns its value as reference.
	 *
	 * @return bool
	 */
	function returnsReference();

}
