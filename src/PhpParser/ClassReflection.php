<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\Inheritor\AnnotationInheritor;
use ApiGen\ElementReflection\Magic\Extractors\MagicElementsExtractor;
use ApiGen\ElementReflection\Parser\DocBlockParser;
use ApiGen\ElementReflection\PhpParser\Builder\ClassLikeElementsBuilderInterface;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;


class ClassReflection extends AbstractClassLikeReflection implements ClassReflectionInterface
{

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var AnnotationInheritor
	 */
	private $annotationInheritor;

	/**
	 * @var MagicElementsExtractor
	 */
	private $magicElementsExtractor;


	public function __construct(
		Class_ $node,
		Namespace_ $parentNode = NULL,
		DocBlockParser $docBlockParser,
		StorageInterface $storage,
		ClassLikeElementsBuilderInterface $classLikeElementsBuilder,
		AnnotationInheritor $annotationInheritor,
		MagicElementsExtractor $magicElementsExtractor
	) {
		parent::__construct($classLikeElementsBuilder);
		$this->node = $node;
		$this->parentNode = $parentNode;
		$this->docBlockParser = $docBlockParser;
		$this->storage = $storage;
		$this->annotationInheritor = $annotationInheritor;
		$this->magicElementsExtractor = $magicElementsExtractor;
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
	public function getShortName()
	{
		$name = $this->getName();
		if ($this->getNamespaceName()) {
			$name = substr($name, strlen($this->getNamespaceName()));
			$name = ltrim($name, self::NS_SEP);
		}
		return $name;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getNamespaceAliases()
	{
		$aliases = [];
		foreach ($this->getParentNode()->stmts as $stmt) {
			if ($stmt instanceof Use_) {
				foreach ($stmt->uses as $use) {
					$aliases[$use->alias] = $use->name->toString();
				}
			}
		}

		if ($this->getParentNode()->name) {
			$namespace = $this->getParentNode()->name->toString();
			$aliases[$namespace] = $namespace;
		}

		return $aliases;
	}


	/**
	 * @return array
	 */
	public function getAnnotations()
	{
		$annotations = parent::getAnnotations();
		if ($this->getParentClass()) {
			$annotations = $this->annotationInheritor->inheritForClass($this, $annotations);
		}
		return $annotations;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isAbstract()
	{
		return $this->node->isAbstract();
	}


	/**
	 * {@inheritdoc}
	 */
	public function isFinal()
	{
		return $this->node->isFinal();
	}


	/**
	 * {@inheritdoc}
	 */
	public function isInterface()
	{
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isException()
	{
		if ($extends = $this->node->extends) {
			if (strpos($extends->parts[0], 'Exception') !== FALSE) {
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParentClass()
	{
		if ($extends = $this->node->extends) {
			$className = $extends->parts[0]; // todo: fix for ns aliases later

			if ( ! class_exists($className)) {
				$parentClassName = $this->getNamespaceName() . self::NS_SEP . $className;

			} else {
				$parentClassName = $className;
			}

			return $this->storage->getClass($parentClassName);
		}

		return NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParentClassNameList()
	{
		return class_parents($this->getName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParentClasses()
	{
		$parent = $this->getParentClass();
		if ($parent == NULL) { // intentionally
			return [];
		}

		return array_merge([$parent->getName() => $parent], $parent->getParentClasses());
	}


	/**
	 * {@inheritdoc}
	 */
	public function isSubclassOf($class)
	{
		if ($this->getParentClass()) {
			if ($class === $this->getParentClass()->getName()) {
				return TRUE;
			}
			if ($this->getParentClass()->isSubclassOf($class)) {
				return TRUE;
			}
		}

		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function implementsInterface($name)
	{
		return isset($this->getInterfaces()[$name]);
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
	public function getInterfaces()
	{
		return parent::getInterfaces();
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
	public function hasProperty($name)
	{
		return isset($this->getProperties()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getProperty($name)
	{
		if ($this->hasProperty($name)) {
			return $this->getProperties()[$name];
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getProperties($filter = NULL)
	{
		return parent::getProperties($filter);
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasOwnProperty($name)
	{
		return isset($this->getOwnProperties()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnProperties($filter = NULL)
	{
		return parent::getOwnProperties($filter);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDirectSubclasses()
	{
		$subclasses = $this->getSubclassesOf($this->getName());
		return array_filter($subclasses, function (ClassReflectionInterface $class) {
			return $class->getParentClass() === NULL || ! $class->getParentClass()->isSubClassOf($this->getName());
		});
	}


	/**
	 * {@inheritdoc}
	 */
	public function getIndirectSubclasses()
	{
		$subclasses = $this->getSubclassesOf($this->getName());
		return array_filter($subclasses, function (ClassReflectionInterface $class) {
			return $class->getParentClass() !== NULL && $class->getParentClass()->isSubClassOf($this->getName());
		});
	}


	/**
	 * {@inheritdoc}
	 */
	public function getTraits()
	{
		return parent::getTraits();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getOwnTraits()
	{
		return parent::getOwnTraits();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getTraitAliases()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getMagicMethods()
	{
		return $this->magicElementsExtractor->getMagicMethodsFromClass($this);
	}


	/**
	 * @param string $name
	 * @return ClassReflectionInterface[]
	 */
	private function getSubclassesOf($name)
	{
		return array_filter($this->storage->getClasses(), function (ClassReflectionInterface $class) use ($name) {
			return $class->isSubclassOf($name);
		});
	}

}
