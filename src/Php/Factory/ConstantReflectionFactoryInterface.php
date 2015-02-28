<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php\Factory;

use ApiGen\ElementReflection\Php\ConstantReflection;


interface ConstantReflectionFactoryInterface
{

	/**
	 * @param string $constantName
	 * @param mixed $constantValue
	 * @return ConstantReflection
	 */
	function create($constantName, $constantValue);

}
