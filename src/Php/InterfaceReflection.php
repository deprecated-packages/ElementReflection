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
use ApiGen\ElementReflection\InterfaceReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Exception\RuntimeException;
use ApiGen\ElementReflection\Php\Factory\ExtensionReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\MethodReflectionFactoryInterface;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ReflectionClass;
use ReflectionMethod;


class InterfaceReflection implements InternalReflectionInterface, InterfaceReflectionInterface, ExtensionInterface
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
	 * @var ExtensionReflectionFactoryInterface
	 */
	private $extensionReflectionFactory;

	/**
	 * @var MethodReflectionFactoryInterface
	 */
	private $methodReflectionFactory;


	/**
	 * @param string $name
	 * @param StorageInterface $storage
	 * @param ExtensionReflectionFactoryInterface $extensionReflectionFactory
	 * @param MethodReflectionFactoryInterface $methodReflectionFactory
	 */
	public function __construct(
		$name,
		StorageInterface $storage,
	    ExtensionReflectionFactoryInterface $extensionReflectionFactory,
		MethodReflectionFactoryInterface $methodReflectionFactory
	) {
		$this->internalReflectionClass = new ReflectionClass($name);
		$this->storage = $storage;
		$this->extensionReflectionFactory = $extensionReflectionFactory;
		$this->methodReflectionFactory = $methodReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->internalReflectionClass->getName();
	}


	/**
	 * @return ExtensionReflection
	 */
	public function getExtension()
	{
		return $this->extensionReflectionFactory->create($this->internalReflectionClass->getExtension()->getName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function implementsInterface($interface)
	{
		if (is_object($interface)) {
			if ( ! $interface instanceof ReflectionClass && ! $interface instanceof ClassReflectionInterface) {
				throw new RuntimeException('Parameter must be a string or an instance of class reflection.');
			}
			$interfaceName = $interface->getName();
			if ( ! $interface->isInterface()) {
				throw new RuntimeException(sprintf('"%s" is not an interface.', $interfaceName));
			}

		} else {
			$interfaceName = $interface;
		}
		return isset($this->getInterfaces()[$interfaceName]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getInterfaces()
	{
		if ($this->interfaces === NULL) {
			$interfaces = [];
			foreach ($this->internalReflectionClass->getInterfaceNames() as $name) {
				$interfaces[$name] = $this->storage->getInterface($name);
			}
			$this->interfaces = $interfaces;
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
			$this->methods = array_map(function (ReflectionMethod $method) {
				return $this->methodReflectionFactory->create(
					$method->getDeclaringClass()->getName(),
					$method->getName()
				);
			}, $this->internalReflectionClass->getMethods());
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
				$this->constants[$name] = new ConstantReflection($name, $value, $this->storage, $this);
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
		return array_diff_assoc($this->internalReflectionClass->getConstants(), $this->getParentClass() ? $this->internalReflectionClass->getParentClass()->getConstants() : []);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDirectImplementers()
	{
		if ( ! $this->isInterface()) {
			return [];
		}
		return array_filter($this->getInternalTokenizedClasses(), function (ClassReflectionInterface $class) {
			if ( ! $class->implementsInterface($this->name)) {
				return FALSE;
			}
			return $class->getParentClass() === NULL || !$class->getParentClass()->implementsInterface($this->name);
		});
	}


	/**
	 * {@inheritdoc}
	 */
	public function getIndirectImplementers()
	{
		if ( ! $this->isInterface()) {
			return [];
		}
		return array_filter($this->getInternalTokenizedClasses(), function (ClassReflectionInterface $class) {
			if ( ! $class->implementsInterface($this->name)) {
				return FALSE;
			}
			return $class->getParentClass() !== NULL && $class->getParentClass()->implementsInterface($this->name);
		});
	}


	/**
	 * @return ClassReflectionInterface[]
	 */
	private function getInternalTokenizedClasses()
	{
		return $this->storage->getInternalClasses();
	}
}
