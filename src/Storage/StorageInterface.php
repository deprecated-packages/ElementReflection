<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Storage;

use ApiGen;
use ApiGen\ElementReflection\InterfaceReflectionInterface;
use ApiGen\ElementReflection\Php\InternalReflectionInterface;
use ApiGen\ElementReflection\Reflection\ReflectionFile;
use ApiGen\ElementReflection\Reflection\ReflectionNamespace;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\ConstantReflectionInterface;
use ApiGen\ElementReflection\FunctionReflectionInterface;
use ApiGen\ElementReflection\ReflectionNamespaceInterface;
use ApiGen\ElementReflection\TraitReflectionInterface;


interface StorageInterface
{

	/**
	 * @param string $name
	 * @param ClassReflectionInterface $reflectionClass
	 */
	function addClass($name, ClassReflectionInterface $reflectionClass);


	/**
	 * Returns if there was such class processed (FQN expected).
	 *
	 * @param string $name
	 * @return bool
	 */
	function hasClass($name);


	/**
	 * Returns a reflection object of the given class (FQN expected).
	 *
	 * @param string $name
	 * @return ClassReflectionInterface|NULL
	 */
	function getClass($name);


	/**
	 * Returns all classes from all namespaces.
	 *
	 * @return ClassReflectionInterface[]
	 */
	function getClasses();


	/**
	 * Returns all internal classes.
	 *
	 * @return InternalReflectionInterface[]|ClassReflectionInterface[]
	 */
	function getInternalClasses();


	/**
	 * @param string $name
	 * @param InterfaceReflectionInterface $interfaceReflection
	 */
	function addInterface($name, InterfaceReflectionInterface $interfaceReflection);


	/**
	 * @param string $name
	 * @return bool
	 */
	function hasInterface($name);


	/**
	 * @param string $name
	 * @return InterfaceReflectionInterface|NULL
	 */
	function getInterface($name);


	/**
	 * @return InterfaceReflectionInterface[]
	 */
	function getInterfaces();


	/**
	 * @param string $name
	 * @param TraitReflectionInterface $traitReflection
	 */
	function addTrait($name, TraitReflectionInterface $traitReflection);


	/**
	 * @param string $name
	 * @return bool
	 */
	function hasTrait($name);


	/**
	 * @param string $name
	 * @return TraitReflectionInterface|NULL
	 */
	function getTrait($name);


	/**
	 * @return TraitReflectionInterface[]
	 */
	function getTraits();


	/**
	 * @param string $name
	 * @param ConstantReflectionInterface $constantReflection
	 */
	function addConstant($name, ConstantReflectionInterface $constantReflection);


	/**
	 * Returns if there was such constant processed (FQN expected).
	 *
	 * @param string $constantName
	 * @return bool
	 */
	function hasConstant($constantName);


	/**
	 * Returns a reflection object of a constant (FQN expected).
	 *
	 * @param string $name
	 * @return ConstantReflectionInterface|NULL
	 */
	function getConstant($name);


	/**
	 * Returns all constants from all namespaces.
	 *
	 * @return array
	 */
	function getConstants();


	/**
	 * @param string $name
	 * @param FunctionReflectionInterface $functionReflection
	 */
	function addFunction($name, FunctionReflectionInterface $functionReflection);


	/**
	 * Returns if there was such function processed (FQN expected).
	 *
	 * @param string $name
	 * @return bool
	 */
	function hasFunction($name);


	/**
	 * Returns a reflection object of a function (FQN expected).
	 *
	 * @param string $name
	 * @return FunctionReflectionInterface|NULL
	 */
	function getFunction($name);


	/**
	 * Returns all functions from all namespaces.
	 *
	 * @return array
	 */
	function getFunctions();

}
