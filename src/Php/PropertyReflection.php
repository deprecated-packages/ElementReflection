<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

use ApiGen\ElementReflection\Behaviors\ExtensionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Php\Factory\ClassReflectionFactoryInterface;
use ReflectionProperty;


class PropertyReflection implements InternalReflectionInterface, ExtensionInterface
{

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var ClassReflectionFactoryInterface
	 */
	private $classReflectionFactory;


	/**
	 * @param mixed $className
	 * @param string $propertyName
	 * @param StorageInterface $storage
	 * @param ClassReflectionFactoryInterface $classReflectionFactory
	 */
	public function __construct(
		$className,
		$propertyName,
		StorageInterface $storage,
		ClassReflectionFactoryInterface $classReflectionFactory
	) {
		$this->internalReflectionProperty = new ReflectionProperty($className, $propertyName);
		$this->storage = $storage;
		$this->classReflectionFactory = $classReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->internalReflectionProperty->getName();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringClass()
	{
		return $this->classReflectionFactory->create($this->internalReflectionProperty->getDeclaringClass()->getName());
	}


	/**
	 * Returns the property default value.
	 *
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		$defaultValues = $this->getDeclaringClass()->getDefaultValues();
		return $defaultValues[$this->getName()];
	}


	/**
	 * {@inheritdoc}
	 */
	public function getExtension()
	{
		return $this->getDeclaringClass()->getExtension();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPrettyName()
	{
		return sprintf('%s::$%s', $this->getDeclaringClass()->getName(), $this->getName());
	}

}
