<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php\Factory;

use ApiGen\ElementReflection\Php\ClassConstantReflection;
use ApiGen\ElementReflection\Php\ClassReflection;


interface ClassConstantReflectionFactoryInterface
{

	/**
	 * @param string $constantName
	 * @param mixed $constantValue
	 * @param ClassReflection $declaringClass
	 * @return ClassConstantReflection
	 */
	function create($constantName, $constantValue, ClassReflection $declaringClass);

}
