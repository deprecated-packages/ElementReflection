<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;


interface ExtensionReflectionInterface extends ReflectionInterface
{

	/**
	 * Returns a class reflection.
	 *
	 * @param string $name
	 * @return ClassReflectionInterface|NULL
	 */
	function getClass($name);


	/**
	 * Returns reflections of classes defined by this extension.
	 *
	 * @return ClassReflectionInterface[]
	 */
	function getClasses();


	/**
	 * Returns a constant value.
	 *
	 * @param string $name
	 * @return mixed|false
	 */
	function getConstant($name);


	/**
	 * Returns values of constants defined by this extension.
	 *
	 * This method exists just for consistence with the rest of reflection.
	 *
	 * @return array
	 */
	function getConstants();


	/**
	 * Returns a function reflection.
	 *
	 * @param string $name
	 * @return FunctionReflectionInterface
	 */
	function getFunction($name);


	/**
	 * Returns reflections of functions defined by this extension.
	 *
	 * @return FunctionReflectionInterface[]
	 */
	function getFunctions();

}
