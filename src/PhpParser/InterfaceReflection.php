<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\InterfaceReflectionInterface;
use ApiGen\ElementReflection\PhpParser\Builder\ClassLikeElementsBuilder;
use ApiGen\ElementReflection\Storage\StorageInterface;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;


class InterfaceReflection extends AbstractClassLikeReflection implements InterfaceReflectionInterface
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	public function __construct(
		Interface_ $node,
		Namespace_ $parentNode = NULL,
		StorageInterface $storage,
		ClassLikeElementsBuilder $classLikeElementsBuilder
	) {
		parent::__construct($classLikeElementsBuilder);
		$this->node = $node;
		$this->parentNode = $parentNode;
		$this->storage = $storage;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return ($this->getNamespaceName() ? $this->getNamespaceName() . self::NS_SEP : '') . $this->node->name;
	}


	/**
	 * {@inheritdoc}
	 */
	public function implementsInterface($interface)
	{
		return isset($this->getInterfaces()[$interface]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getInterfaces()
	{
		return parent::getInterfaces();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnInterfaces()
	{
		return parent::getOwnInterfaces();
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasMethod($name)
	{
		return isset($this->getMethods()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getMethod($name)
	{
		if ($this->hasMethod($name)) {
			return $this->getMethods()[$name];
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getMethods($filter = NULL)
	{
		return parent::getMethods($filter);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnMethods($filter = NULL)
	{
		return parent::getOwnMethods($filter);
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasConstant($name)
	{
		return isset($this->getConstants()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConstant($name)
	{
		if ($this->hasConstant($name)) {
			return $this->getConstants()[$name];
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConstants()
	{
		return parent::getConstants();
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasOwnConstant($name)
	{
		return isset($this->getOwnConstants()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnConstants()
	{
		return parent::getOwnConstants();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDirectImplementers()
	{
		$implementers = $this->getImplementersOf($this->getName());
		return array_filter($implementers, function(ClassReflectionInterface $class) {
			return $class->getParentClass() === NULL || ! $class->getParentClass()->implementsInterface($this->getName());
		});
	}


	/**
	 * {@inheritdoc}
	 */
	public function getIndirectImplementers()
	{
		$implementers = $this->getImplementersOf($this->getName());
		return array_filter($implementers, function(ClassReflectionInterface $class) {
			return $class->getParentClass() !== NULL && $class->getParentClass()->implementsInterface($this->getName());
		});
	}


	/**
	 * @param string $name
	 * @return ClassReflectionInterface[]
	 */
	private function getImplementersOf($name)
	{
		return array_filter($this->storage->getClasses(), function (ClassReflectionInterface $class) use ($name) {
			return $class->implementsInterface($name);
		});
	}

}
