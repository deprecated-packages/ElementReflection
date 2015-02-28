<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Exception\RuntimeException;
use ApiGen\ElementReflection\Php\ExtensionReflection;
use ApiGen\ElementReflection\Php\MethodReflection;
use ApiGen\ElementReflection\Php\PropertyReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use Mockery;


class ClassReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ApiGen\ElementReflection\Php\ClassReflection
	 */
	private $internalReflectionClass;


	protected function setUp()
	{
		parent::setUp();
		$this->internalReflectionClass = $this->parser->getStorage()->getClass('Exception');
	}


	public function testBasicMethods()
	{
		$this->assertTrue($this->internalReflectionClass->isException());
	}


	public function testParents()
	{
		$this->assertNull($this->internalReflectionClass->getParentClass());
		$this->assertSame([], $this->internalReflectionClass->getParentClasses());
		$this->assertSame([], $this->internalReflectionClass->getParentClassNameList());
	}


	public function testMethods()
	{
		$this->assertFalse($this->internalReflectionClass->hasMethod('...'));
		$this->assertFalse($this->internalReflectionClass->hasOwnMethod('...'));
		$this->assertInstanceOf(MethodReflection::class, $this->internalReflectionClass->getMethod('getMessage'));

		$this->assertCount(10, $this->internalReflectionClass->getOwnMethods());
	}


	public function testConstants()
	{
		$this->assertFalse($this->internalReflectionClass->hasOwnConstant('...'));
		$this->assertSame([], $this->internalReflectionClass->getOwnConstants());
	}


	public function testProperties()
	{
		$this->assertInstanceOf(PropertyReflection::class, $this->internalReflectionClass->getProperty('message'));
		$this->assertCount(7, $this->internalReflectionClass->getProperties());
		$this->assertFalse($this->internalReflectionClass->hasOwnProperty('...'));
		$this->assertCount(7, $this->internalReflectionClass->getOwnProperties());
	}


	public function testStaticProperties()
	{
		$this->assertCount(0, $this->internalReflectionClass->getStaticProperties());
	}


	public function testSubclasses()
	{
		$this->assertSame([], $this->internalReflectionClass->getDirectSubclasses());
		$this->assertSame([], $this->internalReflectionClass->getIndirectSubclasses());
	}


	public function testInterfaces()
	{
		$this->assertSame([], $this->internalReflectionClass->getInterfaces());
		$this->assertSame([], $this->internalReflectionClass->getOwnInterfaces());
	}


	public function testSubclassOf()
	{
		$classReflectionMock = Mockery::mock(ApiGen\ElementReflection\PhpParser\ClassReflection::class);
		$classReflectionMock->shouldReceive('getName')->andReturn('SomeClass');
		$this->internalReflectionClass->isSubclassOf($classReflectionMock->getName());
	}


	public function testImplementsInterface()
	{
		$this->assertFalse($this->internalReflectionClass->implementsInterface('Countable'));
	}


	public function testGetExtension()
	{
		$extensionReflection = $this->internalReflectionClass->getExtension();
		$this->assertInstanceOf(ExtensionReflection::class, $extensionReflection);
	}

}
