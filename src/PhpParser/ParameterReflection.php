<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\Analyzers\ValueAnalyzer;
use ApiGen\ElementReflection\MethodReflectionInterface;
use ApiGen\ElementReflection\ParameterReflectionInterface;
use ApiGen\ElementReflection\Parser\DocBlockParser;
use ApiGen\ElementReflection\Storage\StorageInterface;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;


class ParameterReflection extends AbstractReflection implements ParameterReflectionInterface
{

	/**
	 * @var FunctionReflection|MethodReflection
	 */
	private $parentFunction;

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var ValueAnalyzer
	 */
	private $valueAnalyzer;


	public function __construct(
		Param $node,
		FunctionReflection $parentFunction,
		DocBlockParser $docBlockParser,
		StorageInterface $storage,
		ValueAnalyzer $valueAnalyzer
	) {
		$this->node = $node;
		$this->parentFunction = $parentFunction;
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
		return str_replace('()', '($' . $this->getName() . ')', $this->parentFunction->getPrettyName());
	}



	/**
	 * @return string
	 */
	private function getClassName()
	{
		if ($type = $this->node->type) {
			$className = $this->node->type->parts[0];
			if ( ! class_exists($className)) {
				$className = $this->completeNamespace($className);
			}
			return $className;
		}
		return NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getClass()
	{
		$name = $this->completeNamespace($this->getClassName());
		return $this->storage->getClass($name);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringClass()
	{
		if ($this->getDeclaringFunction() instanceof MethodReflectionInterface) {
			return $this->parentFunction->getDeclaringClass();
		}
		return NULL;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDeclaringFunction()
	{
		return $this->parentFunction;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDefaultValue()
	{
		$namespace = $this->getDeclaringClass() ? $this->getDeclaringClass()->getNamespaceName() : $this->getNamespaceName();
		return $this->valueAnalyzer->analyzeFromNodeAndClass($this->node, $this->getDeclaringClass(), $namespace);
	}


	/**
	 * {@inheritdoc}
	 */
	public function isArray()
	{
		if ($this->node->type === 'array') {
			return TRUE;
		}

		if ($this->node->default instanceof Array_) {
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isOptional()
	{
		return (bool) $this->getDefaultValue();
	}


	/**
	 * {@inheritdoc}
	 */
	public function isVariadic()
	{
		return $this->node->variadic;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isPassedByReference()
	{
		return $this->node->byRef;
	}


	/**
	 * @param string $className
	 * @return string
	 */
	private function completeNamespace($className)
	{
		if ($this->getDeclaringFunction() instanceof MethodReflection) {
			$namespace = $this->getDeclaringFunction()->getDeclaringClass()->getParentNode();

		} elseif ($this->getDeclaringFunction() instanceof FunctionReflection) {
			$namespace = $this->getDeclaringFunction()->getParentNode();
		}

		if ($namespace instanceof Namespace_) {
			foreach ($namespace->stmts as $stmt) {
				if ($stmt instanceof Use_) {
					if ($className === $stmt->uses[0]->alias) {
						return implode($stmt->uses[0]->name->parts, ClassReflection::NS_SEP);
					}
				}
			}
		}

		return $className;
	}

}
