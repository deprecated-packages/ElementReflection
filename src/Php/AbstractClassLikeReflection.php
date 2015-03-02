<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

use ApiGen\ElementReflection\Behaviors\ConstantsInterface;
use ApiGen\ElementReflection\Behaviors\ExtensionInterface;
use ApiGen\ElementReflection\Behaviors\InterfacesInterface;
use ApiGen\ElementReflection\Behaviors\MethodsInterface;
use ApiGen\ElementReflection\Exception\RuntimeException;
use ApiGen\ElementReflection\Php\Factory\ClassConstantReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\ExtensionReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\MethodReflectionFactoryInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ReflectionClass;


class AbstractClassLikeReflection implements ConstantsInterface, MethodsInterface, ExtensionInterface,
	InternalReflectionInterface, InterfacesInterface
{

	/**
	 * @var ReflectionClass
	 */
	protected $internalReflectionClass;

	/**
	 * @var PropertyReflection[]
	 */
	protected $properties;

	/**
	 * @var ClassConstantReflectionFactoryInterface
	 */
	protected $classConstantReflectionFactory;

	/**
	 * @var StorageInterface
	 */
	protected $storage;

	/**
	 * @var ClassConstantReflection[]
	 */
	private $constants;

	/**
	 * @var MethodReflection[]
	 */
	private $methods;

	/**
	 * @var InterfaceReflection[]
	 */
	private $interfaces;

	/**
	 * @var MethodReflectionFactoryInterface
	 */
	private $methodReflectionFactory;

	/**
	 * @var ExtensionReflectionFactoryInterface
	 */
	private $extensionReflectionFactory;


	/**
	 * @param string $name
	 * @param MethodReflectionFactoryInterface $methodReflectionFactory
	 * @param ClassConstantReflectionFactoryInterface $classConstantReflectionFactory
	 * @param ExtensionReflectionFactoryInterface $extensionReflectionFactory
	 * @param StorageInterface $storage
	 */
	public function __construct(
		$name,
		MethodReflectionFactoryInterface $methodReflectionFactory,
		ClassConstantReflectionFactoryInterface $classConstantReflectionFactory,
		ExtensionReflectionFactoryInterface $extensionReflectionFactory,
		StorageInterface $storage
	) {
		$this->internalReflectionClass = new ReflectionClass($name);
		$this->methodReflectionFactory = $methodReflectionFactory;
		$this->classConstantReflectionFactory = $classConstantReflectionFactory;
		$this->extensionReflectionFactory = $extensionReflectionFactory;
		$this->storage = $storage;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->internalReflectionClass->getName();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConstants()
	{
		if ($this->constants === NULL) {
			$this->constants = [];
			foreach ($this->internalReflectionClass->getConstants() as $name => $value) {
				$this->constants[$name] = $this->classConstantReflectionFactory->create($name, $value, $this);
			}
		}
		return $this->constants;
	}


	/**
	 * @param string $name
	 * @return bool
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
		throw new RuntimeException(sprintf('Constant "%s" does not exist.', $name));
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasOwnConstant($name)
	{
		return isset($this->getOwnConstants()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnConstants()
	{
		return array_diff_assoc($this->internalReflectionClass->getConstants(), $this->getParentClass()
				? $this->internalReflectionClass->getParentClass()->getConstants()
				: []
		);
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasMethod($name)
	{
		return isset($this->getMethods()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getMethod($name)
	{
		foreach ($this->getMethods() as $method) {
			if ($name === $method->getName()) {
				return $method;
			}
		}
		throw new RuntimeException(sprintf('Method %s does not exist.', $name));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getMethods($filter = NULL)
	{
		if ($this->methods === NULL) {
			$methods = [];
			foreach ($this->internalReflectionClass->getMethods() as $method) {
				$methods[$method->getName()] = $this->methodReflectionFactory->create(
					$method->class,
					$method->getName()
				);
			}
			$this->methods = $methods;
		}
		return $this->methods;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasOwnMethod($name)
	{
		return isset($this->getOwnMethods()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnMethods($filter = NULL)
	{
		return array_filter($this->getMethods(), function (MethodReflection $method) {
			return $this->getName() === $method->getDeclaringClass()->getName();
		});
	}


	/**
	 * {@inheritdoc}
	 */
	public function getInterfaces()
	{
		if ($this->interfaces === NULL) {
			$interface = [];
			foreach ($this->internalReflectionClass->getInterfaceNames() as $name) {
				$interface[$name] = $this->storage->getInterface($name);
			}
			$this->interfaces = $interface;
		}
		return $this->interfaces;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnInterfaces()
	{
		$parent = $this->internalReflectionClass->getParentClass();
		if ($parent) {
			return array_diff_key($this->getInterfaces(), $parent->getInterfaces());

		} else {
			return $this->getInterfaces();
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function getExtension()
	{
		return $this->extensionReflectionFactory->create($this->internalReflectionClass->getExtension()->getName());
	}

}
