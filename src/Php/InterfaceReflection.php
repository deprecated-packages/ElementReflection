<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Php;

use ApiGen;
use ApiGen\ElementReflection\InterfaceReflectionInterface;
use ApiGen\ElementReflection\Exception\RuntimeException;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ReflectionClass;


class InterfaceReflection extends AbstractClassLikeReflection implements InterfaceReflectionInterface
{

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
	public function getDirectImplementers()
	{
		return array_filter($this->getInternalTokenizedClasses(), function (ClassReflectionInterface $class) {
			if ( ! $class->implementsInterface($this->getName())) {
				return FALSE;
			}
			return $class->getParentClass() === NULL || !$class->getParentClass()->implementsInterface($this->getName());
		});
	}


	/**
	 * {@inheritdoc}
	 */
	public function getIndirectImplementers()
	{
		return array_filter($this->getInternalTokenizedClasses(), function (ClassReflectionInterface $class) {
			if ( ! $class->implementsInterface($this->getName())) {
				return FALSE;
			}
			return $class->getParentClass() !== NULL && $class->getParentClass()->implementsInterface($this->getName());
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
