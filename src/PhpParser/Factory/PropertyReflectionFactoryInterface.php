<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Factory;

use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\PhpParser\PropertyReflection;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;


interface PropertyReflectionFactoryInterface
{

	/**
	 * @return PropertyReflection
	 */
	function create(PropertyProperty $node, Property $propertyInfoNode, ClassLikeReflectionInterface $parentClass);

}
