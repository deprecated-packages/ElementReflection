<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\Behaviors\ConstantsInterface;
use ApiGen\ElementReflection\Behaviors\InterfacesInterface;
use ApiGen\ElementReflection\Behaviors\MethodsInterface;
use ApiGen\ElementReflection\Behaviors\TraitsInterface;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\ConstantReflectionInterface;
use ApiGen\ElementReflection\InterfaceReflectionInterface;
use ApiGen\ElementReflection\MethodReflectionInterface;
use ApiGen\ElementReflection\PhpParser\Builder\ClassLikeElementsBuilderInterface;
use ApiGen\ElementReflection\PropertyReflectionInterface;
use ApiGen\ElementReflection\TraitReflectionInterface;


abstract class AbstractClassLikeReflection extends AbstractReflection
{

	/**
	 * @var ClassLikeElementsBuilderInterface
	 */
	protected $classLikeElementsBuilder;


	public function __construct(ClassLikeElementsBuilderInterface $classLikeElementsBuilder)
	{
		$this->classLikeElementsBuilder = $classLikeElementsBuilder;
	}


	/**
	 * @return ConstantReflectionInterface[]
	 */
	protected function getConstants()
	{
		$constants = [];

		if ($this instanceof ConstantsInterface) {
			$constants = array_merge($this->getOwnConstants(), $constants);
		}

		if ($this instanceof ClassReflectionInterface && $this->getParentClass()) {
			$constants = array_merge($this->getParentClass()->getConstants(), $constants);
		}

		if ($this instanceof InterfacesInterface) {
			foreach ($this->getInterfaces() as $interface) {
				$constants = array_merge($interface->getConstants(), $constants);
			}
		}

		return $constants;
	}


	/**
	 * @return ClassConstantReflection[]
	 */
	protected function getOwnConstants()
	{
		return $this->classLikeElementsBuilder->buildConstants($this->node->stmts, $this);
	}


	/**
	 * @param int $filter
	 * @return PropertyReflectionInterface[]
	 */
	protected function getProperties($filter = NULL)
	{
		$properties = [];

		if ($this instanceof ClassReflectionInterface || $this instanceof TraitReflectionInterface) {
			$properties = array_merge($properties, $this->getOwnProperties());
		}

		if ($this instanceof ClassReflectionInterface && $this->getParentClass()) {
			$properties = array_merge($this->getParentClass()->getProperties(), $properties);
		}

		if ($this instanceof TraitsInterface) {
			foreach ($this->getTraits() as $trait) {
				$properties = array_merge($properties, $trait->getProperties());
			}
		}

		return $properties;
	}


	/**
	 * @param int $filter
	 * @return PropertyReflection[]
	 */
	protected function getOwnProperties($filter = NULL)
	{
		return $this->classLikeElementsBuilder->buildProperties($this->node->stmts, $this);
	}


	/**
	 * @return MethodReflectionInterface[]
	 */
	protected function getMethods()
	{
		$methods = [];

		if ($this instanceof MethodsInterface) {
			$methods = $this->getOwnMethods();
		}

		if ($this instanceof ClassReflectionInterface) {
			if ($this->getParentClass()) {
				$methods = array_merge($this->getParentClass()->getMethods(), $methods);
			}

		} elseif ($this instanceof InterfaceReflectionInterface) {
			foreach ($this->getInterfaces() as $interface) {
				$methods = array_merge($interface->getMethods(), $methods);
			}
		}

		if ($this instanceof TraitsInterface) {
			foreach ($this->getTraits() as $trait) {
				$methods = array_merge($trait->getMethods(), $methods);
			}
		}

		return $methods;
	}


	/**
	 * @return MethodReflectionInterface[]
	 */
	protected function getOwnMethods($filter)
	{
		return $this->classLikeElementsBuilder->buildMethods($this->node->stmts, $this);
	}


	/**
	 * @return InterfaceReflectionInterface[]
	 */
	protected function getInterfaces()
	{
		$interfaces = [];

		if ($this instanceof InterfacesInterface) {
			$interfaces = $this->getOwnInterfaces();
		}

		if ($this instanceof ClassReflectionInterface && $this->getParentClass()) {
			$interfaces = array_merge($this->getParentClass()->getInterfaces(), $interfaces);
		}

		if ($this instanceof InterfacesInterface) {
			foreach ($interfaces as $interface) {
				if ($interface instanceof InterfaceReflectionInterface) { // includes internal interfaces
					$interfaces = array_merge((array) $interface->getInterfaces(), $interfaces);
				}
			}
		}

		return $interfaces;
	}


	/**
	 * @return InterfaceReflectionInterface[]
	 */
	protected function getOwnInterfaces()
	{
		if ($this instanceof InterfaceReflectionInterface) {
			/** @var AbstractReflection|AbstractClassLikeReflection $this */
			return $this->classLikeElementsBuilder->buildInterfaces($this->node->extends, $this);

		} elseif ($this instanceof ClassReflectionInterface) {
			/** @var AbstractReflection|AbstractClassLikeReflection $this */
			return $this->classLikeElementsBuilder->buildInterfaces($this->node->implements, $this);
		}
	}


	/**
	 * @return TraitReflectionInterface[]
	 */
	protected function getTraits()
	{
		$traits = $this->getOwnTraits();

		if ($this instanceof TraitsInterface) {
			foreach ($this->getOwnTraits() as $trait) {
				$traits = array_merge($trait->getTraits(), $traits);
			}
		}

		return $traits;
	}


	/**
	 * @return TraitReflectionInterface[]
	 */
	protected function getOwnTraits()
	{
		return $this->classLikeElementsBuilder->buildTraits($this->node->stmts, $this);
	}

}
