<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

use ApiGen\ElementReflection\Behaviors\ExtensionInterface;
use ApiGen\ElementReflection\Php\Factory\ClassReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\Factory\FunctionReflectionFactoryInterface;
use ReflectionParameter;


class ParameterReflection implements InternalReflectionInterface, ExtensionInterface
{

	/**
	 * @var ClassReflectionFactoryInterface
	 */
	private $classReflectionFactory;

	/**
	 * @var FunctionReflectionFactoryInterface
	 */
	private $functionReflectionFactory;


	/**
	 * @param string $functionName
	 * @param string $parameterName
	 * @param NULL $className
	 * @param ClassReflectionFactoryInterface $classReflectionFactory
	 * @param FunctionReflectionFactoryInterface $functionReflectionFactory
	 */
	public function __construct(
		$functionName,
		$parameterName,
		$className = NULL,
		ClassReflectionFactoryInterface $classReflectionFactory,
		FunctionReflectionFactoryInterface $functionReflectionFactory
	) {
		$functionName = $className ? [$className, $functionName] : $functionName;
		$this->internalReflectionParameter = new ReflectionParameter($functionName, $parameterName);
		$this->classReflectionFactory = $classReflectionFactory;
		$this->functionReflectionFactory = $functionReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->internalReflectionParameter->getName();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringClass()
	{
		$class = $this->internalReflectionParameter->getDeclaringClass();
		return $class ? $this->classReflectionFactory->create($class->getName()) : NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getExtension()
	{
		return $this->getDeclaringFunction()->getExtension();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringFunction()
	{
		$class = $this->getDeclaringClass();
		$function = $this->internalReflectionParameter->getDeclaringFunction();
		return $class ? $class->getMethod($function->getName())
			: $this->functionReflectionFactory->create($function->getName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPrettyName()
	{
		return str_replace('()', '($' . $this->getName() . ')', $this->getDeclaringFunction()->getPrettyName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function isVariadic()
	{
		return PHP_VERSION_ID >= 50600 && $this->internalReflectionParameter->isVariadic();
	}

}
