<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;


interface MethodReflectionInterface extends ReflectionFunctionBaseInterface
{

	/**
	 * Returns an element pretty (docblock compatible) name.
	 *
	 * @return string
	 */
	function getPrettyName();


	/**
	 * Returns the declaring class reflection.
	 *
	 * @return ClassReflectionInterface|NULL
	 */
	function getDeclaringClass();


	/**
	 * Returns if the method is abstract.
	 *
	 * @return bool
	 */
	function isAbstract();


	/**
	 * Returns if the method is final.
	 *
	 * @return bool
	 */
	function isFinal();


	/**
	 * Returns if the method is private.
	 *
	 * @return bool
	 */
	function isPrivate();


	/**
	 * Returns if the method is protected.
	 *
	 * @return bool
	 */
	function isProtected();


	/**
	 * Returns if the method is public.
	 *
	 * @return bool
	 */
	function isPublic();


	/**
	 * Returns if the method is static.
	 *
	 * @return bool
	 */
	function isStatic();


	/**
	 * Returns the defining trait.
	 *
	 * @return ClassReflectionInterface|null
	 */
	function getDeclaringTrait();

}
