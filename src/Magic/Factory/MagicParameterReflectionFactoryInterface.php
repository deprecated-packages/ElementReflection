<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Magic\Factory;

use ApiGen\ElementReflection\Magic\MagicMethodReflection;
use ApiGen\ElementReflection\Magic\MagicParameterReflection;


interface MagicParameterReflectionFactoryInterface
{

	/**
	 * @param string $name
	 * @param string $typeHint
	 * @param mixed $defaultValue
	 * @param bool $passedByReference
	 * @param MagicMethodReflection $declaringMethod
	 * @return MagicParameterReflection
	 */
	function create($name, $typeHint, $defaultValue, $passedByReference, MagicMethodReflection $declaringMethod);

}
