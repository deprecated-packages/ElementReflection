<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Magic;

use ApiGen\ElementReflection\Magic\Factory\MagicReflectionInterface;


class MagicParameterReflection implements MagicReflectionInterface
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $typeHint;

	/**
	 * @var mixed
	 */
	private $defaultValue;

	/**
	 * @var bool
	 */
	private $isPassedByReference;

	/**
	 * @var MagicMethodReflection
	 */
	private $declaringMethod;


	/**
	 * @param string $name
	 * @param string $typeHint
	 * @param string $defaultValue
	 * @param bool $passedByReference
	 * @param MagicMethodReflection $declaringMethod
	 */
	public function __construct(
		$name,
		$typeHint,
		$defaultValue,
		$passedByReference,
		MagicMethodReflection $declaringMethod
	) {
		$this->name = $name;
		$this->typeHint = $typeHint;
		$this->defaultValue = $defaultValue;
		$this->isPassedByReference = ($passedByReference === '&');
		$this->declaringMethod = $declaringMethod;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return bool
	 */
	public function isPassedByReference()
	{
		return $this->isPassedByReference;
	}


	/**
	 * @return MagicMethodReflection
	 */
	public function getDeclaringMethod()
	{
		return $this->declaringMethod;
	}


	/**
	 * @return string
	 */
	public function getTypeHint()
	{
		return $this->typeHint;
	}


	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->defaultValue;
	}

}
