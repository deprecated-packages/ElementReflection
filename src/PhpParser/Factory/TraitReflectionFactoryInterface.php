<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Factory;

use ApiGen\ElementReflection\PhpParser\TraitReflection;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Trait_;


interface TraitReflectionFactoryInterface
{

	/**
	 * @return TraitReflection
	 */
	function create(Trait_ $node, Stmt $parentNode = NULL);

}
