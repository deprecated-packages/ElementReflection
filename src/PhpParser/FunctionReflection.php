<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\PhpParser;

use ApiGen\ElementReflection\Exception\RuntimeException;
use ApiGen\ElementReflection\Parser\DocBlockParser;
use ApiGen\ElementReflection\PhpParser\Factory\ParameterReflectionFactoryInterface;
use ApiGen\ElementReflection\FunctionReflectionInterface;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Function_;


class FunctionReflection extends AbstractReflection implements FunctionReflectionInterface
{

	/**
	 * @var ParameterReflectionFactoryInterface
	 */
	protected $parameterReflectionFactory;

	/**
	 * @var ParameterReflection[]
	 */
	private $parameters;


	public function __construct(
		Function_ $node,
		Stmt $parentNode = NULL,
		DocBlockParser $docBlockParser,
		ParameterReflectionFactoryInterface $parameterReflectionFactory
	) {
		$this->node = $node;
		$this->parentNode = $parentNode;
		$this->docBlockParser = $docBlockParser;
		$this->parameterReflectionFactory = $parameterReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return ($this->getNamespaceName() ? $this->getNamespaceName() . self::NS_SEP  : ''). $this->node->name;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPrettyName()
	{
		return $this->getName() . '()';
	}


	/**
	 * {@inheritdoc}
	 */
	public function returnsReference()
	{
		return $this->node->byRef;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isVariadic()
	{
		foreach ($this->getParameters() as $parameter) {
			if ($parameter->isVariadic()) {
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameter($nameOrPosition)
	{
		if (is_numeric($nameOrPosition)) {
			$keys = array_keys($this->getParameters());
			if (isset($keys[$nameOrPosition])) {
				return $this->getParameters()[$keys[$nameOrPosition]];
			}

		} elseif (isset($this->getParameters()[$nameOrPosition])) {
			return $this->getParameters()[$nameOrPosition];
		}

		throw new RuntimeException('There is no parameter with name/position "' . $nameOrPosition . '".');
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameters()
	{
		if ($this->parameters === NULL) {
			if ($this->node->params) {
				foreach ($this->node->params as $nodeParam) {
					$parameterReflection = $this->parameterReflectionFactory->create($nodeParam, $this);
					$this->parameters[$parameterReflection->getName()] = $parameterReflection;
				}
			}
		}
		return $this->parameters;
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
	 * @return Stmt
	 */
	public function getParentNode()
	{
		return $this->parentNode;
	}

}
