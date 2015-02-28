<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\Analyzers\FqnNameAnalyzer;
use ApiGen\ElementReflection\PhpParser\Builder\ClassLikeElementsBuilderInterface;
use ApiGen\ElementReflection\TraitReflectionInterface;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Trait_;
use UnexpectedValueException;


class TraitReflection extends AbstractClassLikeReflection implements TraitReflectionInterface
{

	/**
	 * @var FqnNameAnalyzer
	 */
	private $fqnNameAnalyzer;


	public function __construct(
		Trait_ $node,
		Stmt $parentNode = NULL,
		ClassLikeElementsBuilderInterface $classLikeElementsBuilder,
		FqnNameAnalyzer $fqnNameAnalyzer
	) {
		parent::__construct($classLikeElementsBuilder);
		$this->node = $node;
		$this->parentNode = $parentNode;
		$this->fqnNameAnalyzer = $fqnNameAnalyzer;
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
		throw new UnexpectedValueException("Method '$name' is not available");
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

}
