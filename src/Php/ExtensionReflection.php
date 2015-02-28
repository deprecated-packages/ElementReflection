<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Exception;
use ApiGen\ElementReflection\Php\Factory\ExtensionReflectionFactoryInterface;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\ConstantReflectionInterface;
use ApiGen\ElementReflection\ExtensionReflectionInterface;
use ApiGen\ElementReflection\FunctionReflectionInterface;
use Reflector;
use ReflectionExtension as InternalReflectionExtension;


class ExtensionReflection extends InternalReflectionExtension implements InternalReflectionInterface,
	ExtensionReflectionInterface
{

	/**
	 * @var array|ClassReflectionInterface[]
	 */
	private $classes;

	/**
	 * @var array|ConstantReflectionInterface[]
	 */
	private $constants;

	/**
	 * @var array|FunctionReflectionInterface[]
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
		parent::__construct($name);
		$this->storage = $storage;
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
			}, $this->getClassNames());
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
			}, array_keys(parent::getConstants()));
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
			}, array_keys(parent::getFunctions()));
		}
		return (array) $this->functions;
	}

}
