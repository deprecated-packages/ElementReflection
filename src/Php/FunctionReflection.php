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
use ApiGen\ElementReflection\Parser;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Exception\RuntimeException;
use ApiGen\ElementReflection\Php\Factory\ExtensionReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\ParameterReflectionFactoryInterface;
use ReflectionFunction as InternalReflectionFunction;
use ReflectionParameter as InternalReflectionParameter;


class FunctionReflection extends InternalReflectionFunction implements InternalReflectionInterface,
	ExtensionInterface
{

	/**
	 * Function parameter reflections.
	 *
	 * @var array
	 */
	private $parameters;

	/**
	 * @var Parser
	 */
	private $storage;

	/**
	 * @var ParameterReflectionFactoryInterface
	 */
	private $parameterReflectionFactory;

	/**
	 * @var ExtensionReflectionFactoryInterface
	 */
	private $extensionReflectionFactory;


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
		parent::__construct($name);
		$this->storage = $storage;
		$this->extensionReflectionFactory = $extensionReflectionFactory;
		$this->parameterReflectionFactory = $parameterReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getExtension()
	{
		return $this->extensionReflectionFactory->create(parent::getExtension()->getName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameter($parameter)
	{
		$parameters = $this->getParameters();
		if (is_numeric($parameter)) {
			if ( ! isset($parameters[$parameter])) {
				throw new RuntimeException(sprintf('There is no parameter at position "%d".', $parameter));
			}
			return $parameters[$parameter];

		} else {
			foreach ($parameters as $reflection) {
				if ($reflection->getName() === $parameter) {
					return $reflection;
				}
			}
			throw new RuntimeException(sprintf('There is no parameter "%s".', $parameter));
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameters()
	{
		if ($this->parameters === NULL) {
			$this->parameters = array_map(function (InternalReflectionParameter $parameter) {
				return $this->parameterReflectionFactory->create(
					$parameter->getDeclaringFunction()->getName(),
					$parameter->getName()
				);
			}, parent::getParameters());
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
		return PHP_VERSION_ID >= 50600 ? parent::isVariadic() : FALSE;
	}

}
