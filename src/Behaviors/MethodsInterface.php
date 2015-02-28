<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Behaviors;

use ApiGen\ElementReflection\MethodReflectionInterface;


interface MethodsInterface
{

	/**
	 * Returns if the class implements the given method.
	 *
	 * @param string $name
	 * @return bool
	 */
	function hasMethod($name);


	/**
	 * Returns a method reflection.
	 *
	 * @param string $name
	 * @return MethodReflectionInterface
	 */
	function getMethod($name);


	/**
	 * Returns method reflections.
	 *
	 * @param int $filter Methods filter
	 * @return MethodReflectionInterface[]
	 */
	function getMethods($filter = NULL);


	/**
	 * Returns method reflections declared by this class, not its parents.
	 *
	 * @param int $filter Methods filter
	 * @return MethodReflectionInterface[]
	 */
	function getOwnMethods($filter = NULL);

	// todo: public/protected/private

}
