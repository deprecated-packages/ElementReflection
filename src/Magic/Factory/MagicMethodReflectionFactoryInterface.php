<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Magic\Factory;

use ApiGen\ElementReflection\Magic\MagicMethodReflection;
use ApiGen\ElementReflection\PhpParser\ClassReflection;


interface MagicMethodReflectionFactoryInterface
{

	/**
	 * @param string $name
	 * @param string $shortDescription
	 * @param string $returnType
	 * @param bool $returnsReference
	 * @param array $arguments
	 * @param string $declaringAnnotation
	 * @param ClassReflection $declaringClass
	 * @return MagicMethodReflection
	 */
	function create(
		$name,
		$shortDescription,
		$returnType,
		$returnsReference,
		$arguments,
		$declaringAnnotation,
		ClassReflection $declaringClass
	);

}
