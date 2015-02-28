<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection;

use ApiGen\ElementReflection\PhpParser\InterfaceReflection;
use ApiGen\ElementReflection\PhpParser\MethodReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection\MethodsSource\MethodsInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class MethodsTest extends ParserAwareTestCase
{

	/**
	 * @var InterfaceReflection
	 */
	private $interfaceReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/MethodsSource');
		$this->interfaceReflection = $storage->getInterface(MethodsInterface::class);
	}


	public function testMethods()
	{
		$methods = $this->interfaceReflection->getMethods();
		$this->assertCount(2, $methods);
		$this->assertArrayHasKey('someMethod', $methods);
		$this->assertArrayHasKey('someParentMethod', $methods);

		$this->assertTrue($this->interfaceReflection->hasMethod('someMethod'));
		$this->assertInstanceOf(
			MethodReflection::class,
			$this->interfaceReflection->getMethod('someMethod')
		);

		$this->assertFalse($this->interfaceReflection->getMethod('someNonExistingMethod'));
	}


	public function testOwndMethods()
	{
		$ownMethods = $this->interfaceReflection->getOwnMethods();
		$this->assertCount(1, $ownMethods);
		$this->assertArrayHasKey('someMethod', $ownMethods);
	}

}
