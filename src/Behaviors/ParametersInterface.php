<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Behaviors;

use ApiGen\ElementReflection\ParameterReflectionInterface;


interface ParametersInterface
{

	/**
	 * Returns a function/method parameter.
	 *
	 * @param int|string $parameter Parameter name or position
	 * @return ParameterReflectionInterface
	 */
	function getParameter($parameter);


	/**
	 * Returns function/method parameters.
	 *
	 * @return ParameterReflectionInterface[]
	 */
	function getParameters();

}
