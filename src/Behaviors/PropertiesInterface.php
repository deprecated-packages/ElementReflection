<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Behaviors;

use ApiGen\ElementReflection\PropertyReflectionInterface;


interface PropertiesInterface
{

	/**
	 * Returns if the class defines the given property.
	 *
	 * @param string $name
	 * @return bool
	 */
	function hasProperty($name);


	/**
	 * Return a property reflection.
	 *
	 * @param string $name
	 * @return PropertyReflectionInterface
	 */
	function getProperty($name);


	/**
	 * Returns property reflections.
	 *
	 * @param int $filter Properties filter
	 * @return array|PropertyReflectionInterface[]
	 */
	function getProperties($filter = NULL);


	/**
	 * Returns if the class (and not its parents) defines the given property.
	 *
	 * @param string $name Property name
	 * @return bool
	 */
	function hasOwnProperty($name);


	/**
	 * Returns property reflections declared by this class, not its parents.
	 *
	 * @param int $filter Properties filter
	 * @return PropertyReflectionInterface[]
	 */
	function getOwnProperties($filter = NULL);

}
