<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Factory;

use ApiGen\ElementReflection\PhpParser\FunctionReflection;
use ApiGen\ElementReflection\PhpParser\ParameterReflection;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;


interface ParameterReflectionFactoryInterface
{

	/**
	 * @return ParameterReflection
	 */
	function create(Param $node, FunctionReflection $parentFunction);

}
