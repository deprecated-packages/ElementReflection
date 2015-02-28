<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection;

use ApiGen\ElementReflection\PhpParser\MethodReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\MethodsSource\ClassMethodsChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\MethodsSource\NoMethodsClass;


class MethodsTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/MethodsSource');
	}


	public function testMethods()
	{
		$classReflection = $this->storage->getClass(ClassMethodsChildClass::class);
		$methods = [
			'__construct', '__destruct', 'publicFinalFunction', 'publicStaticFunction', 'protectedStaticFunction',
			'privateStaticFunction', 'publicFunction', 'protectedFunction', 'privateFunction'
		];

		foreach ($methods as $method) {
			$this->assertTrue($classReflection->hasMethod($method));
			$this->assertInstanceOf(MethodReflection::class, $classReflection->getMethod($method));
		}
		$this->assertFalse($classReflection->hasMethod('nonExistent'));
	}


	public function testNoMethods()
	{
		$classReflection = $this->storage->getClass(NoMethodsClass::class);

		$this->assertSame([], $classReflection->getMethods());
		$this->assertSame([], $classReflection->getOwnMethods());
		$this->assertFalse($classReflection->hasMethod('nonExistent'));
	}

}
