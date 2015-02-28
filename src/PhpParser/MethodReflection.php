<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\Inheritor\AnnotationInheritor;
use ApiGen\ElementReflection\MethodReflectionInterface;
use ApiGen\ElementReflection\Parser\DocBlockParser;
use ApiGen\ElementReflection\PhpParser\Factory\ParameterReflectionFactoryInterface;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;


class MethodReflection extends FunctionReflection implements MethodReflectionInterface
{

	/**
	 * @var AnnotationInheritor
	 */
	private $annotationInheritor;

	/**
	 * @var ClassReflection|TraitReflection
	 */
	private $declaringClass;


	public function __construct(
		ClassMethod $node,
		ClassLikeReflectionInterface $declaringClass,
		DocBlockParser $docBlockParser,
		ParameterReflectionFactoryInterface $parameterReflectionFactory,
		AnnotationInheritor $annotationInheritor
	) {
		$this->node = $node;
		$this->declaringClass = $declaringClass;
		$this->docBlockParser = $docBlockParser;
		$this->parameterReflectionFactory = $parameterReflectionFactory;
		$this->annotationInheritor = $annotationInheritor;
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
		return $this->name . '()'; // @todo!
	}


	/**
	 * {@inheritdoc}
	 * @todo figure out later
	 */
	public function getNamespaceAliases()
	{
		return [];
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringClass()
	{
		return $this->declaringClass;
	}


	/**
	 * @return Stmt
	 */
	public function getParentNode()
	{
		return $this->parentNode;
	}


	/**
	 * @return array
	 */
	public function getAnnotations()
	{
		$annotations = parent::getAnnotations();
		if ($this->getDeclaringClass()->getParentClass()) {
			$annotations = $this->annotationInheritor->inheritForMethod($this, $annotations);
		}
		return $annotations;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isAbstract()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function isFinal()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function isPrivate()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function isProtected()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function isPublic()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function isStatic()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringTrait()
	{
	}

}
