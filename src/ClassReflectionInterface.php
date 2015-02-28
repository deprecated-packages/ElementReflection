<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;

use ApiGen\ElementReflection\Behaviors\AnnotationsInterface;
use ApiGen\ElementReflection\Behaviors\ConstantsInterface;
use ApiGen\ElementReflection\Behaviors\InterfacesInterface;
use ApiGen\ElementReflection\Behaviors\MethodsInterface;
use ApiGen\ElementReflection\Behaviors\NamespacesInterface;
use ApiGen\ElementReflection\Behaviors\PropertiesInterface;
use ApiGen\ElementReflection\Behaviors\StartEndLineInterface;
use ApiGen\ElementReflection\Behaviors\TraitsInterface;


interface ClassReflectionInterface extends ClassLikeReflectionInterface, ConstantsInterface, PropertiesInterface,
	MethodsInterface, StartEndLineInterface, AnnotationsInterface, NamespacesInterface, TraitsInterface,
	InterfacesInterface
{

	/**
	 * Returns the unqualified name (UQN).
	 *
	 * @return string
	 */
	function getShortName();


	/**
	 * Returns if the class is abstract.
	 *
	 * @return bool
	 */
	function isAbstract();


	/**
	 * Returns if the class is final.
	 *
	 * @return bool
	 */
	function isFinal();


	/**
	 * Returns if the class is an exception or its descendant.
	 *
	 * @return bool
	 */
	function isException();


	/**
	 * Returns if the current class is a subclass of the given class.
	 *
	 * @param string|object $class Class name or reflection object
	 * @return bool
	 */
	function isSubclassOf($class);


	/**
	 * Returns the parent class reflection.
	 *
	 * @return ClassReflectionInterface|NULL
	 */
	function getParentClass();


	/**
	 * Returns the parent classes reflections.
	 *
	 * @return ClassReflectionInterface[]
	 */
	function getParentClasses();


	/**
	 * Returns the parent classes names.
	 *
	 * @return string[]
	 */
	function getParentClassNameList();


	/**
	 * Returns if the class implements the given interface.
	 *
	 * @param string|object $interface Interface name or reflection object
	 * @return bool
	 */
	function implementsInterface($interface);


	/**
	 * Returns reflections of direct subclasses.
	 *
	 * @return ClassReflectionInterface[]
	 */
	function getDirectSubclasses();


	/**
	 * Returns reflections of indirect subclasses.
	 *
	 * @return ClassReflectionInterface[]
	 */
	function getIndirectSubclasses();

}
