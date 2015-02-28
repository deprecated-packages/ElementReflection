<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\Behaviors\AnnotationsInterface;
use ApiGen\ElementReflection\Parser\DocBlockParser;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;


abstract class AbstractReflection implements AnnotationsInterface
{

	/**
	 * @var string
	 */
	const NS_SEP = '\\';

	/**
	 * @var Stmt
	 */
	protected $node;

	/**
	 * @var Stmt
	 */
	protected $parentNode;

	/**
	 * @var DocBlockParser
	 */
	protected $docBlockParser;


	/**
	 * {@inheritdoc}
	 */
	public function getNamespaceName()
	{
		if ($this->parentNode instanceof Namespace_) {
			return implode($this->parentNode->name->parts, self::NS_SEP);

		} elseif ($this instanceof PropertyReflection && $class = $this->getDeclaringClass()) {
			/** @var ClassReflection $class */
			return $class->getNamespaceName();

		} elseif ($this instanceof ParameterReflection && $parentNode = $this->getDeclaringFunction()->getParentNode()) {
			return implode($parentNode->name->parts, self::NS_SEP);
		}

		return '';
	}


	/**
	 * {@inheritdoc}
	 */
	public function inNamespace()
	{
		return (bool) $this->getNamespaceName();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getStartLine()
	{
		return $this->node->getAttribute('startLine');
	}


	/**
	 * {@inheritdoc}
	 */
	public function getEndLine()
	{
		return $this->node->getAttribute('endLine');
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDocComment()
	{
		if ($docComment = $this->node->getDocComment()) {
			return $docComment->getText();
		}
		return '';
	}


	/**
	 * {@inheritdoc}
	 */
	public function isDeprecated()
	{
		if ($this->hasAnnotation('deprecated')) {
			return TRUE;
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasAnnotation($name)
	{
		return isset($this->getAnnotations()[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getAnnotation($name)
	{
		if ($this->hasAnnotation($name)) {
			return $this->getAnnotations()[$name];
		}
		return NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getAnnotations()
	{
		return $this->docBlockParser->parseToAnnotations($this->getDocComment());
	}


	/**
	 * @return Stmt
	 */
	public function getParentNode()
	{
		return $this->parentNode;
	}

}
