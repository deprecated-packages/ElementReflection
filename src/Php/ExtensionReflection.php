<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

use ApiGen\ElementReflection\ClassConstantReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\ExtensionReflectionInterface;
use ApiGen\ElementReflection\FunctionReflectionInterface;
use ReflectionExtension;


class ExtensionReflection implements InternalReflectionInterface,
	ExtensionReflectionInterface
{

	/**
	 * @var ClassReflectionInterface[]
	 */
	private $classes;

	/**
	 * @var ClassConstantReflectionInterface[]
	 */
	private $constants;

	/**
	 * @var FunctionReflectionInterface[]
	 */
	private $functions;

	/**
	 * @var StorageInterface
	 */
	private $storage;


	/**
	 * @param string $name
	 * @param StorageInterface $storage
	 */
	public function __construct($name, StorageInterface $storage)
	{
		$this->internalReflectionExtension = new ReflectionExtension($name);
		$this->storage = $storage;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->internalReflectionExtension->getName();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getClass($name)
	{
		$classes = $this->getClasses();
		return isset($classes[$name]) ? $classes[$name] : NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getClasses()
	{
		if ($this->classes === NULL) {
			$this->classes = array_map(function ($className) {
				return $this->storage->getClass($className);
			}, $this->internalReflectionExtension->getClassNames());
		}
		return $this->classes;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConstant($name)
	{
		$constants = $this->getConstants();
		return isset($constants[$name]) ? $constants[$name] : NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConstants()
	{
		if ($this->constants === NULL) {
			$this->constants = array_map(function ($constantName) {
				return $this->storage->getConstant($constantName);
			}, array_keys($this->internalReflectionExtension->getConstants()));
		}
		return $this->constants;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFunction($name)
	{
		$functions = $this->getFunctions();
		return isset($functions[$name]) ? $functions[$name] : NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		if ($this->functions === NULL) {
			$this->classes = array_map(function ($functionName) {
				return $this->storage->getFunction($functionName);
			}, array_keys($this->internalReflectionExtension->getFunctions()));
		}
		return (array) $this->functions;
	}

}
