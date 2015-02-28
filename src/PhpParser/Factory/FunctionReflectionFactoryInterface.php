<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Factory;

use ApiGen\ElementReflection\PhpParser\FunctionReflection;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Function_;


interface FunctionReflectionFactoryInterface
{

	/**
	 * @return FunctionReflection
	 */
	function create(Function_ $node, Stmt $parentNode = NULL);

}
