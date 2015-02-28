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
use ApiGen\ElementReflection\Php\Factory\FunctionReflectionFactoryInterface;
use ReflectionParameter as InternalReflectionParameter;


class ParameterReflection extends InternalReflectionParameter implements InternalReflectionInterface,
	ExtensionInterface
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
	 * @param null $className
	 * @param StorageInterface $storage
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
		if ($className) {
			parent::__construct([$className, $functionName], $parameterName);

		} else {
			parent::__construct($functionName, $parameterName);
		}
		$this->classReflectionFactory = $classReflectionFactory;
		$this->functionReflectionFactory = $functionReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringClass()
	{
		$class = parent::getDeclaringClass();
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
	public function getExtensionName()
	{
		$extension = $this->getExtension();
		return $extension ? $extension->getName() : FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringFunction()
	{
		$class = $this->getDeclaringClass();
		$function = parent::getDeclaringFunction();
		return $class ? $class->getMethod($function->getName()) : $this->functionReflectionFactory->create($function->getName());
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
		return PHP_VERSION_ID >= 50600 && parent::isVariadic();
	}

}
