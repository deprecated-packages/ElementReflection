<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Factory;

use ApiGen\ElementReflection\PhpParser\ConstantReflection;
use PhpParser;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Const_;


interface ConstantReflectionFactoryInterface
{

	/**
	 * @return ConstantReflection
	 */
	function create(Const_ $node, Stmt $parentNode = NULL);

}
