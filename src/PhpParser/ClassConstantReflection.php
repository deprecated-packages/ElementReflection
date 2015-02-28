<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\ClassConstantReflectionInterface;
use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\Parser\DocBlockParser;
use ApiGen\ElementReflection\Storage\StorageInterface;
use PhpParser;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;


class ClassConstantReflection extends AbstractReflection implements ClassConstantReflectionInterface
{

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var FunctionReflection
	 */
	private $parentClass;


	public function __construct(
		Const_ $node,
		ClassLikeReflectionInterface $parentClass,
		DocBlockParser $docBlockParser,
		StorageInterface $storage
	) {
		$this->node = $node;
		$this->parentClass = $parentClass;
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
		return $this->getDeclaringClass()->getName() . '::' . $this->getShortName();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getShortName()
	{
		return $this->node->name;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringClass()
	{
		return $this->parentClass;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringTrait()
	{
		return $this->parentClass;
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
		if ($this->node->value instanceof ClassConstFetch) {
			$where = $this->node->value->class->parts[0];
			$constantName = $this->node->value->name;
			if ($where === 'self') {
				$constant = $this->getDeclaringClass()->getConstant($constantName);
				return $constant->getValue();

			} elseif ($where === 'parent') {
				$constant = $this->getDeclaringClass()->getParentClass()->getConstant($constantName);
				return $constant->getValue();
			}

		} elseif ($this->node->value instanceof ConstFetch) {
			$constantName = $this->node->value->name;
			$constant = $this->storage->getConstant($constantName);
			return $constant->getValue();

		} else {
			return $this->node->value->value;
		}
	}

}
