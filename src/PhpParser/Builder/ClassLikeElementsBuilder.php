<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser\Builder;

use ApiGen\ElementReflection\Analyzers\FqnNameAnalyzer;
use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\PhpParser\AbstractReflection;
use ApiGen\ElementReflection\PhpParser\Factory\ClassConstantReflectionFactoryInterface;
use ApiGen\ElementReflection\PhpParser\Factory\MethodReflectionFactoryInterface;
use ApiGen\ElementReflection\PhpParser\Factory\PropertyReflectionFactoryInterface;
use ApiGen\ElementReflection\PhpParser\Factory\TraitReflectionFactoryInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\TraitUse;


class ClassLikeElementsBuilder implements ClassLikeElementsBuilderInterface
{

	/**
	 * @var ClassConstantReflectionFactoryInterface
	 */
	private $classConstantReflectionFactory;

	/**
	 * @var MethodReflectionFactoryInterface
	 */
	private $methodReflectionFactory;

	/**
	 * @var PropertyReflectionFactoryInterface
	 */
	private $propertyReflectionFactory;

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var TraitReflectionFactoryInterface
	 */
	private $traitReflectionFactory;

	/**
	 * @var FqnNameAnalyzer
	 */
	private $fqnNameAnalyzer;


	public function __construct(
		ClassConstantReflectionFactoryInterface $classConstantReflectionFactory,
		MethodReflectionFactoryInterface $methodReflectionFactory,
		PropertyReflectionFactoryInterface $propertyReflectionFactory,
		TraitReflectionFactoryInterface $traitReflectionFactory,
		StorageInterface $storage,
		FqnNameAnalyzer $fqnNameAnalyzer
	) {
		$this->classConstantReflectionFactory = $classConstantReflectionFactory;
		$this->methodReflectionFactory = $methodReflectionFactory;
		$this->propertyReflectionFactory = $propertyReflectionFactory;
		$this->traitReflectionFactory = $traitReflectionFactory;
		$this->storage = $storage;
		$this->fqnNameAnalyzer = $fqnNameAnalyzer;
	}


	/**
	 * {@inheritdoc}
	 */
	public function buildConstants(array $stmts, ClassLikeReflectionInterface $parentClass)
	{
		$constants = [];
		foreach ($stmts as $stmt) {
			if ($stmt instanceof ClassConst) {
				foreach ($stmt->consts as $constantStmt) {
					$constant = $this->classConstantReflectionFactory->create($constantStmt, $parentClass);
					$constants[$constant->getShortName()] = $constant;
				}
			}
		}
		return $constants;
	}


	/**
	 * {@inheritdoc}
	 */
	public function buildProperties(array $stmts, ClassLikeReflectionInterface $parentClass)
	{
		$properties = [];
		foreach ($stmts as $stmt) {
			if ($stmt instanceof Property) {
				foreach ($stmt->props as $propertyStmt) {
					$property = $this->propertyReflectionFactory->create($propertyStmt, $stmt, $parentClass);
					$properties[$property->getName()] = $property;
				}
			}
		}
		return $properties;
	}


	/**
	 * {@inheritdoc}
	 */
	public function buildMethods(array $stmts, ClassLikeReflectionInterface $parentClass)
	{
		$methods = [];
		foreach ($stmts as $stmt) {
			if ($stmt instanceof ClassMethod) {
				$method = $this->methodReflectionFactory->create($stmt, $parentClass);
				$methods[$method->getName()] = $method;
			}
		}
		return $methods;
	}


	/**
	 * {@inheritdoc}
	 */
	public function buildInterfaces(array $stmts, ClassLikeReflectionInterface $parentClass)
	{
		$interfaces = [];
		foreach ($stmts as $stmt) {
			$interfaceName = $this->detectInterfaceName($stmt, $parentClass->getParentNode());
			$interfaces[$interfaceName] = $this->storage->getInterface($interfaceName);
		}
		return $interfaces;
	}


	/**
	 * {@inheritdoc}
	 */
	public function buildTraits(array $stmts, ClassLikeReflectionInterface $parentClass)
	{
		$traits = [];
		foreach ($stmts as $stmt) {
			if ($stmt instanceof TraitUse) {
				foreach ($stmt->traits as $trait) {
					$traitName = $this->fqnNameAnalyzer->detectFqnName($trait, $parentClass);
					$traits[$traitName] = $this->storage->getTrait($traitName);
				}
			}
		}
		return $traits;
	}


	/**
	 * @param string|Interface_|Name $interface
	 * @param Node $parentNode
	 * @return string
	 */
	private function detectInterfaceName($interface, $parentNode = NULL)
	{
		$name = $interface->toString();

		if ($interface instanceof Name && $parentNode instanceof Namespace_)  {
			$namespacedName = $parentNode->name->toString() . AbstractReflection::NS_SEP . $name;
			if (interface_exists($namespacedName)) {
				return $namespacedName;
			}
		}

		if (interface_exists($name)) {
			return $name;
		}

		return '';
	}

}
