<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Factory;

use ApiGen\ElementReflection\PhpParser\ClassReflection;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;


interface ClassReflectionFactoryInterface
{

	/**
	 * @return ClassReflection
	 */
	function create(Class_ $node, Namespace_ $parentNode = NULL);

}
