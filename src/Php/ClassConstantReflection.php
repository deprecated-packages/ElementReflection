<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

use ApiGen\ElementReflection\Storage\StorageInterface;


class ClassConstantReflection implements InternalReflectionInterface
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $declaringClassName;

	/**
	 * @var mixed
	 */
	private $value;

	/**
	 * @var StorageInterface
	 */
	private $storage;


	/**
	 * @param string $name
	 * @param mixed $value
	 * @param ClassReflection $declaringClass
	 * @param StorageInterface $storage
	 */
	public function __construct($name, $value, ClassReflection $declaringClass, StorageInterface $storage)
	{
		$this->name = $name;
		$this->value = $value;
		$this->storage = $storage;

		$realParent = NULL;
		if (array_key_exists($name, $declaringClass->getOwnConstants())) {
			$realParent = $declaringClass;
		}
		if ($realParent === NULL) {
			foreach ($declaringClass->getParentClasses() as $grandParent) {
				if (array_key_exists($name, $grandParent->getOwnConstants())) {
					$realParent = $grandParent;
					break;
				}
			}
		}
		$this->declaringClassName = $realParent->getName();
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
	public function getDeclaringClass()
	{
		return $this->storage->getClass($this->declaringClassName);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPrettyName()
	{
		return sprintf('%s::%s', $this->declaringClassName, $this->name);
	}

}
