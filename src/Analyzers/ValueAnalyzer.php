<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Analyzers;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\PhpParser\AbstractReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Stmt;
use PhpParser\NodeAbstract;


class ValueAnalyzer
{

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var FqnNameAnalyzer
	 */
	private $fqnNameAnalyzer;


	public function __construct(StorageInterface $storage, FqnNameAnalyzer $fqnNameAnalyzer)
	{
		$this->storage = $storage;
		$this->fqnNameAnalyzer = $fqnNameAnalyzer;
	}


	/**
	 * @param NodeAbstract $node
	 * @param ClassReflectionInterface $declaringClass
	 * @param null $namespaceName
	 * @return mixed
	 */
	public function analyzeFromNodeAndClass(NodeAbstract $node, ClassReflectionInterface $declaringClass = NULL, $namespaceName = NULL)
	{
		if ($node->default) {
			if ($node->default instanceof Array_) { // todo: traverse array deeper
				$value = [];
				foreach ($node->default->items as $node) {
					if ($this->isConstantFetch($node->value)) {
						$value[] = $this->getConstantValue($node->value, $declaringClass, $namespaceName);
					}
				}
				return $value;

			} elseif ($this->isConstantFetch($node->default)) {
				return $this->getConstantValue($node->default, $declaringClass, $namespaceName);

			} else {
				return $node->default->value;
			}
		}
		return NULL;
	}


	/**
	 * @param ConstFetch|ClassConstFetch $constFetch
	 * @return mixed
	 */
	private function getConstantValue($constFetch, ClassReflectionInterface $declaringClass = NULL, $namespaceName)
	{
		if ($constFetch instanceof ConstFetch) {
			$name = $this->fqnNameAnalyzer->compactFqnName($constFetch->name, $namespaceName);
			return $this->storage->getConstant($name)->getValue();

		} elseif ($constFetch instanceof ClassConstFetch) {
			$where = $constFetch->class->toString();
			$constantName = $constFetch->name;
			if ($where === 'self') {
				if ($value = $this->getClassConstantValue($declaringClass, $constantName)) {
					return $value;
				}

			} elseif ($where === 'parent') {
				if ($value = $this->getClassConstantValue($declaringClass->getParentClass(), $constantName)) {
					return $value;
				}

			} else {
				foreach ($declaringClass->getNamespaceAliases() as $alias => $real) {
					if ($where === $alias) {
						$namespacedClassName = $real;

					} else {
						$namespacedClassName = $real . AbstractReflection::NS_SEP . $where;
					}

					if ($this->storage->hasClass($namespacedClassName)) {
						$classReflection = $this->storage->getClass($namespacedClassName);
						if ($value = $this->getClassConstantValue($classReflection, $constantName)) {
							return $value;
						}
					}
				}
			}

			$name = $this->fqnNameAnalyzer->compactFqnName($constFetch->name, $namespaceName);
			if ($this->storage->hasClass($name)) {
				$class = $this->storage->getClass($name);
				if ($value = $this->getClassConstantValue($class, $constantName)) {
					return $value;
				}
			}
		}
	}


	/**
	 * @param ClassConstFetch|ConstFetch $value
	 * @return bool
	 */
	private function isConstantFetch($value)
	{
		if ($value instanceof ClassConstFetch || $value instanceof ConstFetch) {
			return TRUE;
		}
		return FALSE;
	}


	/**
	 * @param ClassReflectionInterface $classReflection
	 * @param string $constantName
	 * @return mixed|NULL
	 */
	private function getClassConstantValue(ClassReflectionInterface $classReflection, $constantName)
	{
		if ($classReflection->hasConstant($constantName)) {
			$constant = $classReflection->getConstant($constantName);
			if ($constant) {
				return $constant->getValue();
			}
		}
		return NULL;
	}

}
