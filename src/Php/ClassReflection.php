<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

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


class ClassReflection extends AbstractClassLikeReflection implements PropertiesInterface
{

	/**
	 * @var ClassReflectionFactoryInterface
	 */
	private $classReflectionFactory;

	/**
	 * @var PropertyReflectionFactoryInterface
	 */
	private $propertyReflectionFactory;


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
		parent::__construct(
			$name, $methodReflectionFactory, $classConstantReflectionFactory, $extensionReflectionFactory, $storage
		);
		$this->classReflectionFactory = $classReflectionFactory;
		$this->propertyReflectionFactory = $propertyReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isException()
	{
		return $this->getName() === 'Exception' || $this->isSubclassOf('Exception');
	}


	/**
	 * @param string $className
	 * @return bool
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
	public function implementsInterface($interfaceName)
	{
		return isset($this->getInterfaces()[$interfaceName]);
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
	 * @return array
	 */
	public function getDefaultValues()
	{
		return $this->internalReflectionClass->getDefaultProperties();
	}


	/**
	 * @return string[]
	 */
	public function getParentClassNameList()
	{
		return class_parents($this->getName());
	}


	/**
	 * @return ClassReflectionInterface[]
	 */
	private function getInternalTokenizedClasses()
	{
		return $this->storage->getInternalClasses();
	}

}
