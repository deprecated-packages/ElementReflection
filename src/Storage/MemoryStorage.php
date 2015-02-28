<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Storage;

use ApiGen\ElementReflection\Exception\NonExistingElementException;
use ApiGen\ElementReflection\InterfaceReflectionInterface;
use ApiGen\ElementReflection\Php\ClassReflection;
use ApiGen\ElementReflection\Php\ConstantReflection;
use ApiGen\ElementReflection\Php\Factory\ClassReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\FunctionReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\InterfaceReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\FunctionReflection;
use ApiGen\ElementReflection\Php\InterfaceReflection;
use ApiGen\ElementReflection\Php\InternalReflectionInterface;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\ConstantReflectionInterface;
use ApiGen\ElementReflection\FunctionReflectionInterface;
use ApiGen\ElementReflection\TraitReflectionInterface;


class MemoryStorage implements StorageInterface
{

	/**
	 * @var ConstantReflectionInterface[]
	 */
	private $constants = [];

	/**
	 * @var ClassReflectionInterface[]
	 */
	private $classes = [];

	/**
	 * @var FunctionReflectionInterface[]
	 */
	private $functions = [];

	/**
	 * @var InterfaceReflectionInterface[]
	 */
	private $interfaces = [];

	/**
	 * @var TraitReflectionInterface[]
	 */
	private $traits = [];

	/**
	 * @var string[]
	 */
	private $declaredClasses;

	/**
	 * @var string[]
	 */
	private $declaredConstants;

	/**
	 * @var string[]
	 */
	private $declaredFunctions;

	/**
	 * @var string[]
	 */
	private $declaredInterfaces;

	/**
	 * @var ClassReflectionFactoryInterface
	 */
	private $classReflectionFactory;

	/**
	 * @var FunctionReflectionFactoryInterface
	 */
	private $functionReflectionFactory;

	/**
	 * @var InterfaceReflectionFactoryInterface
	 */
	private $interfaceReflectionFactory;


	public function __construct(
		ClassReflectionFactoryInterface $classReflectionFactory,
		FunctionReflectionFactoryInterface $functionReflectionFactory,
		InterfaceReflectionFactoryInterface $interfaceReflectionFactory
	) {
		$this->classReflectionFactory = $classReflectionFactory;
		$this->functionReflectionFactory = $functionReflectionFactory;
		$this->interfaceReflectionFactory = $interfaceReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function addClass($name, ClassReflectionInterface $reflectionClass)
	{
		$this->classes[$name] = $reflectionClass;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasClass($name)
	{
		if (isset($this->classes[$name])) {
			return TRUE;
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getClass($name)
	{
		if (isset($this->classes[$name])) {
			return $this->classes[$name];
		}

		if (isset($this->getDeclaredClasses()[$name])) {
			return $this->classReflectionFactory->create($name);
		}

		return NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getClasses()
	{
		return $this->classes;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getInternalClasses()
	{
		$internalClasses = [];
		foreach ($this->classes as $className => $class) {
			foreach (array_merge($class->getParentClasses(), $class->getInterfaces()) as $parent) {
				/** @var ClassReflectionInterface $parent */
				if ($parent instanceof InternalReflectionInterface) {
					$internalClasses[$parent->getName()] = $parent;
				}
			}
		}
		return $internalClasses;
	}


	/**
	 * {@inheritdoc}
	 */
	public function addConstant($name, ConstantReflectionInterface $constantReflection)
	{
		$this->constants[$name] = $constantReflection;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasConstant($name)
	{
		return isset($this->getConstants()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConstant($name)
	{
		if ($this->hasConstant($name)) {
			return $this->getConstants()[$name];
		}

		if (isset($this->getDeclaredConstants()[$name])) {
			return new ConstantReflection($name, $this->getDeclaredConstants()[$name], $this);
		}

		throw new NonExistingElementException(sprintf('Constant %s does not exist', $name));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConstants()
	{
		return $this->constants;
	}


	/**
	 * {@inheritdoc}
	 */
	public function addFunction($name, FunctionReflectionInterface $reflectionFunction)
	{
		$this->functions[$name] = $reflectionFunction;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasFunction($name)
	{
		if (isset($this->functions[$name])) {
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFunction($name)
	{
		if ($this->hasFunction($name)) {
			return $this->getFunctions()[$name];
		}

		if (isset($this->getDeclaredFunctions()[$name])) {
			return $this->functionReflectionFactory->create($name);
		}

		throw new NonExistingElementException(sprintf('Function %s does not exist', $name));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return $this->functions;
	}


	/**
	 * @return string[]
	 */
	private function getDeclaredClasses()
	{
		if ($this->declaredClasses === NULL) {
			$this->declaredClasses = array_flip(get_declared_classes());
		}
		return $this->declaredClasses;
	}


	/**
	 * @return string[]
	 */
	private function getDeclaredInterfaces()
	{
		if ($this->declaredInterfaces === NULL) {
			$this->declaredInterfaces = array_flip(get_declared_interfaces());
		}
		return $this->declaredInterfaces;

	}


	/**
	 * @return array
	 */
	private function getDeclaredConstants()
	{
		if ($this->declaredConstants === NULL) {
			$this->declaredConstants = get_defined_constants();
		}
		return $this->declaredConstants;
	}


	/**
	 * @return array
	 */
	private function getDeclaredFunctions()
	{
		if ($this->declaredFunctions === NULL) {
			$this->declaredFunctions = array_flip(get_defined_functions()['internal']);
		}
		return $this->declaredFunctions;
	}


	/**
	 * {@inheritdoc}
	 */
	public function addInterface($name, InterfaceReflectionInterface $interfaceReflection)
	{
		$this->interfaces[$name] = $interfaceReflection;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasInterface($name)
	{
		return (bool) isset($this->interfaces[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getInterface($name)
	{
		if ($this->hasInterface($name)) {
			return $this->interfaces[$name];
		}

		if (isset($this->getDeclaredInterfaces()[$name])) {
			return $this->interfaceReflectionFactory->create($name);
		}

		throw new NonExistingElementException(sprintf('Interface %s does not exist.', $name));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getInterfaces()
	{
		return $this->interfaces;
	}


	/**
	 * {@inheritdoc}
	 */
	public function addTrait($name, TraitReflectionInterface $traitReflection)
	{
		$this->traits[$name] = $traitReflection;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasTrait($name)
	{
		return isset($this->traits[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getTrait($name)
	{
		if ($this->hasTrait($name)) {
			return $this->traits[$name];
		}

		throw new NonExistingElementException(sprintf('Trait %s does not exist.', $name));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getTraits()
	{
		return $this->traits;
	}

}
