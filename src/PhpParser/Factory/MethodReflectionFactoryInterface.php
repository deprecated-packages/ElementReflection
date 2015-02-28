<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Factory;

use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\PhpParser\MethodReflection;
use PhpParser\Node\Stmt\ClassMethod;


interface MethodReflectionFactoryInterface
{

	/**
	 * @return MethodReflection
	 */
	function create(ClassMethod $node, ClassLikeReflectionInterface $declaringClass);

}
