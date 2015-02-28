<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Factory;

use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\PhpParser\ClassConstantReflection;
use PhpParser;
use PhpParser\Node\Const_;


interface ClassConstantReflectionFactoryInterface
{

	/**
	 * @return ClassConstantReflection
	 */
	function create(Const_ $node, ClassLikeReflectionInterface $parentClass);

}
