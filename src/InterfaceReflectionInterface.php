<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;

use ApiGen\ElementReflection\Behaviors\ConstantsInterface;
use ApiGen\ElementReflection\Behaviors\InterfacesInterface;
use ApiGen\ElementReflection\Behaviors\MethodsInterface;


interface InterfaceReflectionInterface extends ClassLikeReflectionInterface, ConstantsInterface, MethodsInterface, InterfacesInterface
{

	/**
	 * Returns reflections of classes directly implementing this interface.
	 *
	 * @return ClassReflectionInterface[]
	 */
	function getDirectImplementers();


	/**
	 * Returns reflections of classes indirectly implementing this interface.
	 *
	 * @return ClassReflectionInterface[]
	 */
	function getIndirectImplementers();

}
