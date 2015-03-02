<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

use ApiGen;
use ApiGen\ElementReflection\Behaviors\ExtensionInterface;
use ApiGen\ElementReflection\Behaviors\MethodsInterface;
use ApiGen\ElementReflection\Behaviors\PropertiesInterface;
use ApiGen\ElementReflection\Php\Factory\ClassConstantReflectionFactoryInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Exception;
use ApiGen\ElementReflection\Exception\RuntimeException;
use ApiGen\ElementReflection\Php\Factory\ClassReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\ExtensionReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\MethodReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\PropertyReflectionFactoryInterface;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ReflectionClass;
use ReflectionProperty as InternalReflectionProperty;
use ReflectionMethod as InternalReflectionMethod;


class ClassReflection implements InternalReflectionInterface, PropertiesInterface, MethodsInterface, ExtensionInterface
{

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var InterfaceReflection[]
	 */
	private $interfaces;

	/**
	 * @var MethodReflection[]
	 */
	private $methods;

	/**
	 * @var ClassConstantReflection[]
	 */
	private $constants;

	/**
	 * @var PropertyReflection[]
	 */
	private $properties;

	/**
	 * @var ExtensionReflectionFactoryInterface
	 */
	private $extensionReflectionFactory;

	/**
	 * @var ClassReflectionFactoryInterface
	 */
	private $classReflectionFactory;

	/**
	 * @var MethodReflectionFactoryInterface
	 */
	private $methodReflectionFactory;

	/**
	 * @var PropertyReflectionFactoryInterface
	 */
	private $propertyReflectionFactory;

	/**
	 * @var ClassConstantReflectionFactoryInterface
	 */
	private $classConstantReflectionFactory;

	/**
	 * @var ReflectionClass
	 */
	private $internalReflectionClass;


	/**
	 * @param string $name
	 * @param StorageInterface $storage
	 * @param ExtensionReflectionFactoryInterface $extensionReflectionFactory
	 * @param ClassReflectionFactoryInterface $classReflectionFactory
	 * @param MethodReflectionFactoryInterface $methodReflectionFactory
	 * @param PropertyReflectionFactoryInterface $propertyReflectionFactory
	 * @param ClassConstantReflectionFactoryInterface $classConstantReflectionFactory
	 */
	public function __construct(
		$name,
		StorageInterface $storage,
		ExtensionReflectionFactoryInterface $extensionReflectionFactory,
		ClassReflectionFactoryInterface $classReflectionFactory,
		MethodReflectionFactoryInterface $methodReflectionFactory,
		PropertyReflectionFactoryInterface $propertyReflectionFactory,
		ClassConstantReflectionFactoryInterface $classConstantReflectionFactory
	) {

		$this->internalReflectionClass = new ReflectionClass($name);
		$this->storage = $storage;
		$this->extensionReflectionFactory = $extensionReflectionFactory;
		$this->classReflectionFactory = $classReflectionFactory;
		$this->methodReflectionFactory = $methodReflectionFactory;
		$this->propertyReflectionFactory = $propertyReflectionFactory;
		$this->classConstantReflectionFactory = $classConstantReflectionFactory;
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
	public function getExtension()
	{
		return $this->extensionReflectionFactory->create($this->internalReflectionClass->getExtension()->getName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function isException()
	{
		return $this->getName() === 'Exception' || $this->isSubclassOf('Exception');
	}


	/**
	 * {@inheritdoc}
	 */
	public function isSubclassOf($className)
	{
		return in_array($className, $this->getParentClassNameList());
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParentClass()
	{
		$parent = $this->internalReflectionClass->getParentClass();
		return $parent ? $this->classReflectionFactory->create($parent) : NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParentClasses()
	{
		return array_map(function ($className) {
			return $this->storage->getClass($className);
		}, $this->getParentClassNameList());
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParentClassNameList()
	{
		return class_parents($this->getName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function implementsInterface($interfaceName)
	{
		return isset($this->getInterfaces()[$interfaceName]);
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
		$parent = $this->getParentClass();
		return $parent ? array_diff_key($this->getInterfaces(), $parent->getInterfaces()) : $this->getInterfaces();
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
	public function hasProperty($name)
	{
		return isset($this->getProperties()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getProperty($name)
	{
		foreach ($this->getProperties() as $property) {
			if ($name === $property->getName()) {
				return $property;
			}
		}
		throw new RuntimeException(sprintf('Property %s does not exist.', $name));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getProperties($filter = NULL)
	{
		if ($this->properties === NULL) {
			$properties = [];
			foreach ($this->internalReflectionClass->getProperties() as $property) {
				$properties[$property->getName()] = $this->propertyReflectionFactory->create(
					$property->getDeclaringClass()->getName(),
					$property->getName()
				);
			}
			$this->properties = $properties;
		}
		return $this->properties;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasOwnProperty($name)
	{
		foreach ($this->getOwnProperties() as $property) {
			if ($name === $property->getName()) {
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnProperties($filter = NULL)
	{
		return array_filter($this->getProperties(), function (PropertyReflection $property) {
			return $property->getDeclaringClass()->getName() === $this->getName();
		});
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDirectSubclasses()
	{
		return array_filter($this->getInternalTokenizedClasses(), function (ClassReflectionInterface $class) {
			if ( ! $class->isSubclassOf($this->getName())) {
				return FALSE;
			}
			return $class->getParentClass() === NULL || ! $class->getParentClass()->isSubClassOf($this->getName());
		});
	}


	/**
	 * {@inheritdoc}
	 */
	public function getIndirectSubclasses()
	{
		return array_filter($this->getInternalTokenizedClasses(), function (ClassReflectionInterface $class) {
			if ( ! $class->isSubclassOf($this->getName())) {
				return FALSE;
			}
			return $class->getParentClass() !== NULL && $class->getParentClass()->isSubClassOf($this->getName());
		});
	}


	/**
	 * @return ClassReflectionInterface[]
	 */
	private function getInternalTokenizedClasses()
	{
		return $this->storage->getInternalClasses();
	}


	/**
	 * @return array
	 */
	public function getDefaultValues()
	{
		return $this->internalReflectionClass->getDefaultProperties();
	}

}
