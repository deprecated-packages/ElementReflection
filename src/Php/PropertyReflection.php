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
use ReflectionProperty as InternalReflectionProperty;


class PropertyReflection extends InternalReflectionProperty implements InternalReflectionInterface,
	ExtensionInterface
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
		parent::__construct($className, $propertyName);
		$this->storage = $storage;
		$this->classReflectionFactory = $classReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringClass()
	{
		return $this->classReflectionFactory->create(parent::getDeclaringClass()->getName());
	}


	/**
	 * Returns the property default value.
	 *
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		$values = $this->getDeclaringClass()->getDefaultProperties();
		return $values[$this->getName()];
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
