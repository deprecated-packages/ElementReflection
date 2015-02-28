<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;

use ApiGen\ElementReflection\Behaviors\AnnotationsInterface;
use ApiGen\ElementReflection\Behaviors\StartEndLineInterface;


interface PropertyReflectionInterface extends ReflectionInterface, StartEndLineInterface, AnnotationsInterface
{

	/**
	 * Returns an element pretty (docblock compatible) name.
	 *
	 * @return string
	 */
	function getPrettyName();


	/**
	 * Returns a reflection of the declaring class.
	 *
	 * @return ClassReflectionInterface
	 */
	function getDeclaringClass();


	/**
	 * Returns the property default value.
	 *
	 * @return mixed
	 */
	function getDefaultValue();


	/**
	 * Returns if the property is private.
	 *
	 * @return bool
	 */
	function isPrivate();


	/**
	 * Returns if the property is protected.
	 *
	 * @return bool
	 */
	function isProtected();


	/**
	 * Returns if the property is public.
	 *
	 * @return bool
	 */
	function isPublic();


	/**
	 * Returns if the property is static.
	 *
	 * @return bool
	 */
	function isStatic();


	/**
	 * Returns the defining trait.
	 *
	 * @return ClassReflectionInterface|NULL
	 */
	function getDeclaringTrait();

}
