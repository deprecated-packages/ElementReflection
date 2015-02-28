<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\ConstantReflectionInterface;
use ApiGen\ElementReflection\Parser\DocBlockParser;
use ApiGen\ElementReflection\Storage\StorageInterface;
use PhpParser;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Const_;


class ConstantReflection extends AbstractReflection implements ConstantReflectionInterface
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	public function __construct(
		Const_ $node,
		Stmt $parentNode,
		DocBlockParser $docBlockParser,
		StorageInterface $storage
	) {
		$this->node = $node;
		$this->parentNode = $parentNode;
		$this->docBlockParser = $docBlockParser;
		$this->storage = $storage;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		if ($this->getPrettyName()) {
			return $this->getPrettyName();

		} else {
			return ($this->getNamespaceName() ? $this->getNamespaceName() : '') . '::' . $this->getShortName();
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPrettyName()
	{
		if ($this->getNamespaceName()) {
			return $this->getNamespaceName() . ClassReflection::NS_SEP . $this->getShortName();

		} else {
			return $this->getShortName();
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function getShortName()
	{
		if ($this->node->consts) {
			return $this->node->consts[0]->name;

		} else {
			return $this->node->name;
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function getNamespaceAliases()
	{
		// TODO: Implement getNamespaceAliases() method.
	}


	/**
	 * {@inheritdoc}
	 */
	public function getValue()
	{
		return $this->node->consts[0]->value->value;
	}

}
