<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;


class ConstantReflection implements InternalReflectionInterface
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var mixed
	 */
	private $value;


	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getShortName()
	{
		return $this->getName();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getValue()
	{
		return $this->value;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPrettyName()
	{
		return $this->name;
	}

}
