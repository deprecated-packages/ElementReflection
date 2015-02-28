<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\Analyzers\ValueAnalyzer;
use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\Parser\AnnotationParser;
use ApiGen\ElementReflection\Parser\DocBlockParser;
use ApiGen\ElementReflection\PropertyReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;


class PropertyReflection extends AbstractReflection implements PropertyReflectionInterface
{

	/**
	 * @var Property
	 */
	private $propertyInfoNode;

	/**
	 * @var ClassLikeReflectionInterface
	 */
	private $parentClass;

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var ValueAnalyzer
	 */
	private $valueAnalyzer;


	public function __construct(
		PropertyProperty $node,
		Property $propertyInfoNode,
		ClassLikeReflectionInterface $parentClass,
		DocBlockParser $docBlockParser,
		StorageInterface $storage,
		ValueAnalyzer $valueAnalyzer
	) {
		$this->node = $node;
		$this->propertyInfoNode = $propertyInfoNode;
		$this->parentClass = $parentClass;
		$this->docBlockParser = $docBlockParser;
		$this->storage = $storage;
		$this->valueAnalyzer = $valueAnalyzer;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->node->name;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPrettyName()
	{
		$className = $this->getDeclaringClass() ? $this->getDeclaringClass()->getName() : $this->getDeclaringTrait()->getName();
		return sprintf('%s::$%s', $className, $this->getName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringClass()
	{
		$parentClassName = $this->parentClass->getName();
		if ($this->storage->hasClass($parentClassName)) {
			return $this->storage->getClass($parentClassName);
		}
		return NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringTrait()
	{
		$parentTraitName = $this->parentClass->getName();
		if ($this->storage->hasTrait($parentTraitName)) {
			return $this->storage->getTrait($parentTraitName);
		}
		return NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDefaultValue()
	{
		return $this->valueAnalyzer->analyzeFromNodeAndClass(
			$this->node,
			$this->getDeclaringClass(),
			$this->getNamespaceName()
		);
	}


	/**
	 * {@inheritdoc}
	 */
	public function isPublic()
	{
		return (bool) ($this->propertyInfoNode->type & Class_::MODIFIER_PUBLIC);
	}


	/**
	 * {@inheritdoc}
	 */
	public function isProtected()
	{
		return (bool) ($this->propertyInfoNode->type & Class_::MODIFIER_PROTECTED);
	}


	/**
	 * {@inheritdoc}
	 */
	public function isPrivate()
	{
		return (bool) ($this->propertyInfoNode->type & Class_::MODIFIER_PRIVATE);
	}


	/**
	 * {@inheritdoc}
	 */
	public function isStatic()
	{
		return (bool) ($this->propertyInfoNode->type & Class_::MODIFIER_STATIC);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDocComment()
	{
		$docComment = $this->propertyInfoNode->getDocComment();

//		if ($docComment === NULL) {
//			if ($parentClass = $this->getDeclaringClass()->getParentClass()) {
//				if ($parentClass->hasProperty($this->getName())) {
//					$parentClassProperty = $parentClass->getProperty($this->getName());
//
//					if ($parentClassProperty->getDocComment()) {
//						return $parentClassProperty->getDocComment();
//					}
//				}
//			}
//		}

		if ($docComment instanceof Doc) {
			return $docComment->getText();
		}

		return $docComment;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getAnnotations()
	{
		$annotations = parent::getAnnotations();
		$parentAnnotations = $this->getParentAnnotations();

		// todo: use annotations inheritor!

		// todo: optimize
		if (empty($annotations)) {
			$annotations = $parentAnnotations;
		}

		if (empty($annotations)) {
			return [];
		}

		foreach ($annotations as $type => $annotation) {
			if ( ! is_string($annotation)) {
				continue;
			}
			if (strpos($annotation, AnnotationParser::INHERITDOC) !== FALSE) {
				if ($parentAnnotations && isset($parentAnnotations[$type])) {
					$replaceBy = $parentAnnotations[$type];

				} else {
					$replaceBy = '';
				}
				$annotation = str_replace(AnnotationParser::INHERITDOC, $replaceBy, $annotation);
				$annotations[$type] = $annotation;
			}
		}

		if (empty($annotations['var'])) {
			foreach ($parentAnnotations as $key => $annotation) {
				if ($key === 'var') {
					$annotations['var'] = $annotation;
					break;
				}
			}
		}
		return $annotations;
	}


	/**
	 * @return array|bool
	 */
	private function getParentAnnotations()
	{
		if ($parentClass = $this->getDeclaringClass()->getParentClass()) {
			if ($parentClass->hasProperty($this->getName())) {
				$parentClassProperty = $parentClass->getProperty($this->getName());
				if ($parentClassProperty->getAnnotations()) {
					return $parentClassProperty->getAnnotations();
				}
			}
		}
		return FALSE;
	}

}
