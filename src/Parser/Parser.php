<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Parser;

use ApiGen\ElementReflection\Exception\ParserException;
use ApiGen\ElementReflection\PhpParser\Factory\ClassReflectionFactoryInterface;
use ApiGen\ElementReflection\PhpParser\Factory\ConstantReflectionFactoryInterface;
use ApiGen\ElementReflection\PhpParser\Factory\FunctionReflectionFactoryInterface;
use ApiGen\ElementReflection\PhpParser\Factory\InterfaceReflectionFactoryInterface;
use ApiGen\ElementReflection\PhpParser\Factory\TraitReflectionFactoryInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use Nette\Utils\Finder;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Trait_;
use SplFileInfo;
use PhpParser;


class Parser implements ParserInterface
{

	/**
	 * @var StorageInterface
	 */
	private $storage;

	/**
	 * @var PhpParser\Parser
	 */
	private $phpParser;

	/**
	 * @var ClassReflectionFactoryInterface
	 */
	private $classReflectionFactory;

	/**
	 * @var ConstantReflectionFactoryInterface
	 */
	private $constantReflectionFactory;

	/**
	 * @var InterfaceReflectionFactoryInterface
	 */
	private $interfaceReflectionFactory;

	/**
	 * @var TraitReflectionFactoryInterface
	 */
	private $traitReflectionFactory;

	/**
	 * @var FunctionReflectionFactoryInterface
	 */
	private $functionReflectionFactory;


	public function __construct(
		StorageInterface $storage,
		PhpParser\Parser $phpParser,
		ClassReflectionFactoryInterface $classReflectionFactory,
		ConstantReflectionFactoryInterface $constantReflectionFactory,
		InterfaceReflectionFactoryInterface $interfaceReflectionFactory,
		TraitReflectionFactoryInterface $traitReflectionFactory,
		FunctionReflectionFactoryInterface $functionReflectionFactory
	) {
		$this->storage = $storage;
		$this->phpParser = $phpParser;
		$this->classReflectionFactory = $classReflectionFactory;
		$this->constantReflectionFactory = $constantReflectionFactory;
		$this->interfaceReflectionFactory = $interfaceReflectionFactory;
		$this->traitReflectionFactory = $traitReflectionFactory;
		$this->functionReflectionFactory = $functionReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function parseDirectory($path)
	{
		$realPath = realpath($path);
		if ( ! is_dir($realPath)) {
			throw new ParserException(sprintf('Directory %s does not exist.', $path));
		}

		foreach (Finder::findFiles('*')->in($realPath) as $entry) {
			/** @var SplFileInfo $entry */
			$this->parseFile($entry->getPathName());
		}

		return $this->storage;
	}


	/**
	 * {@inheritdoc}
	 */
	public function parseFile($name)
	{
		if ( ! file_exists($name)) {
			throw new ParserException(sprintf('File %s does not exist.', $name));
		}

		$fileContent = file_get_contents($name);
		$nodes = $this->phpParser->parse($fileContent);
		$this->iterateNodes($nodes);
		return $this->storage;
	}


	/**
	 * @param Node[]|Stmt[] $nodes
	 * @param Node $parentNode
	 */
	private function iterateNodes($nodes, Node $parentNode = NULL)
	{
		foreach ($nodes as $node) {
			switch (get_class($node)) {
				case Class_::class:
					$classReflection = $this->classReflectionFactory->create($node, $parentNode);
					$this->storage->addClass($classReflection->getName(), $classReflection);
					break;
				case Trait_::class:
					$traitReflection = $this->traitReflectionFactory->create($node, $parentNode);
					$this->storage->addTrait($traitReflection->getName(), $traitReflection);
					break;
				case Interface_::class:
					$interfaceReflection = $this->interfaceReflectionFactory->create($node, $parentNode);
					$this->storage->addInterface($interfaceReflection->getName(), $interfaceReflection);
					break;
				case Const_::class:
					$constantReflection = $this->constantReflectionFactory->create($node, $parentNode);
					$this->storage->addConstant($constantReflection->getName(), $constantReflection);
					break;
				case Function_::class:
					$functionReflection = $this->functionReflectionFactory->create($node, $parentNode);
					$this->storage->addFunction($functionReflection->getName(), $functionReflection);
					break;
				case Namespace_::class:
					$this->iterateNodes($node->stmts, $node);
					break;
			}
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function getStorage()
	{
		return $this->storage;
	}

}
