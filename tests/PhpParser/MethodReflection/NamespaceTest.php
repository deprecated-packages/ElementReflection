<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\MethodReflection;

use ApiGen\ElementReflection\MethodReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\MethodReflection\NamespaceSource\InNamespace;
use NoNamespace;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class NamespaceTest extends ParserAwareTestCase
{

	/**
	 * @var MethodReflectionInterface
	 */
	private $methodInNamespaceReflection;

	/**
	 * @var MethodReflectionInterface
	 */
	private $methodWithoutNamespaceReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/NamespaceSource');

		$classInNamespaceReflection = $storage->getClass(InNamespace::class);
		$this->methodInNamespaceReflection = $classInNamespaceReflection->getMethod('inNamespace');

		$classWithoutNamespaceReflection = $storage->getClass(NoNamespace::class);
		$this->methodWithoutNamespaceReflection = $classWithoutNamespaceReflection->getMethod('noNamespace');
	}


	public function testInNamespace()
	{
		$this->assertFalse($this->methodInNamespaceReflection->inNamespace());
		$this->assertSame('', $this->methodInNamespaceReflection->getNamespaceName());
		$this->assertSame('inNamespace', $this->methodInNamespaceReflection->getName());
	}


	public function testWithoutNamespace()
	{
		$this->assertFalse($this->methodWithoutNamespaceReflection->inNamespace());
		$this->assertSame('', $this->methodWithoutNamespaceReflection->getNamespaceName());
		$this->assertSame('noNamespace', $this->methodWithoutNamespaceReflection->getName());
	}

}
