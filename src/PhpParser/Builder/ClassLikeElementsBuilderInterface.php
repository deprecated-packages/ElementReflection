<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Builder;

use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\InterfaceReflectionInterface;
use ApiGen\ElementReflection\PhpParser\ClassConstantReflection;
use ApiGen\ElementReflection\PhpParser\MethodReflection;
use ApiGen\ElementReflection\PhpParser\PropertyReflection;
use ApiGen\ElementReflection\TraitReflectionInterface;
use PhpParser\Node\Name;


interface ClassLikeElementsBuilderInterface
{

	/**
	 * @param array $stmts
	 * @param ClassLikeReflectionInterface $parentClass
	 * @return ClassConstantReflection[]
	 */
	function buildConstants(array $stmts, ClassLikeReflectionInterface $parentClass);


	/**
	 * @param array $stmts
	 * @param ClassLikeReflectionInterface $parentClass
	 * @return PropertyReflection[]
	 */
	function buildProperties(array $stmts, ClassLikeReflectionInterface $parentClass);


	/**
	 * @param array $stmts
	 * @param ClassLikeReflectionInterface $parentClass
	 * @return MethodReflection[]
	 */
	function buildMethods(array $stmts, ClassLikeReflectionInterface $parentClass);


	/**
	 * @param array|Name[] $stmts
	 * @param ClassLikeReflectionInterface $parentClass
	 * @return InterfaceReflectionInterface[]
	 */
	function buildInterfaces(array $stmts, ClassLikeReflectionInterface $parentClass);


	/**
	 * @param array|Name[] $stmts
	 * @param ClassLikeReflectionInterface $parentClass
	 * @return TraitReflectionInterface[]
	 */
	function buildTraits(array $stmts, ClassLikeReflectionInterface $parentClass);

}
