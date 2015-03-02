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
use ApiGen\ElementReflection\Exception\RuntimeException;
use ApiGen\ElementReflection\Php\Factory\ExtensionReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\ParameterReflectionFactoryInterface;
use ReflectionFunction;


class FunctionReflection implements InternalReflectionInterface, ExtensionInterface
{

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var ParameterReflection[]
	 */
	private $parameters;

	/**
	 * @var ParameterReflectionFactoryInterface
	 */
	private $parameterReflectionFactory;

	/**
	 * @var ExtensionReflectionFactoryInterface
	 */
	private $extensionReflectionFactory;

	/**
	 * @var ReflectionFunction
	 */
	private $internalReflectionFunction;


	/**
	 * @param string $name
	 * @param StorageInterface $storage
	 * @param ExtensionReflectionFactoryInterface $extensionReflectionFactory
	 * @param ParameterReflectionFactoryInterface $parameterReflectionFactory
	 */
	public function __construct(
		$name,
		StorageInterface $storage,
		ExtensionReflectionFactoryInterface $extensionReflectionFactory,
		ParameterReflectionFactoryInterface $parameterReflectionFactory
	) {
		$this->internalReflectionFunction = new ReflectionFunction($name);
		$this->storage = $storage;
		$this->extensionReflectionFactory = $extensionReflectionFactory;
		$this->parameterReflectionFactory = $parameterReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->internalReflectionFunction->getName();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getExtension()
	{
		return $this->extensionReflectionFactory->create($this->internalReflectionFunction->getExtension()->getName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameter($name)
	{
		foreach ($this->getParameters() as $parameters) {
			if ($parameters->getName() === $name) {
				return $parameters;
			}
		}
		throw new RuntimeException(sprintf('There is no parameter "%s".', $name));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameters()
	{
		if ($this->parameters === NULL) {
			$parameters = [];
			foreach ($this->internalReflectionFunction->getParameters() as $parameter) {
				$parameters[$parameter->getName()] = $this->parameterReflectionFactory->create(
					$parameter->getDeclaringFunction()->getName(),
					$parameter->getName()
				);
			}
			$this->parameters = $parameters;
		}
		return $this->parameters;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPrettyName()
	{
		return $this->getName() . '()';
	}


	/**
	 * {@inheritdoc}
	 */
	public function isVariadic()
	{
		return PHP_VERSION_ID >= 50600 ? $this->internalReflectionFunction->isVariadic() : FALSE;
	}

}
