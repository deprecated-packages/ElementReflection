<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Behaviors;

use ApiGen\ElementReflection\InterfaceReflectionInterface;


interface InterfacesInterface
{

	/**
	 * @return InterfaceReflectionInterface[]
	 */
	function getInterfaces();


	/**
	 * Returns interface reflections implemented by this class, not its parents.
	 *
	 * @return InterfaceReflectionInterface[]
	 */
	function getOwnInterfaces();

}
