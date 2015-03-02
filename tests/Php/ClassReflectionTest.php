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
	private $classReflection;


	protected function setUp()
	{
		parent::setUp();
		$this->classReflection = $this->parser->getStorage()->getClass('Exception');
	}


	public function testBasicMethods()
	{
		$this->assertTrue($this->classReflection->isException());
	}


	public function testParents()
	{
		$this->assertNull($this->classReflection->getParentClass());
		$this->assertSame([], $this->classReflection->getParentClasses());
		$this->assertSame([], $this->classReflection->getParentClassNameList());
	}


	public function testMethods()
	{
		$this->assertFalse($this->classReflection->hasMethod('...'));
		$this->assertFalse($this->classReflection->hasOwnMethod('...'));
		$this->assertInstanceOf(MethodReflection::class, $this->classReflection->getMethod('getMessage'));

		$this->assertCount(10, $this->classReflection->getOwnMethods());
	}


	public function testConstants()
	{
		$this->assertFalse($this->classReflection->hasOwnConstant('...'));
		$this->assertSame([], $this->classReflection->getOwnConstants());
	}


	public function testProperties()
	{
		$this->assertInstanceOf(PropertyReflection::class, $this->classReflection->getProperty('message'));
		$this->assertCount(7, $this->classReflection->getProperties());
		$this->assertFalse($this->classReflection->hasOwnProperty('...'));
		$this->assertCount(7, $this->classReflection->getOwnProperties());
	}


	public function testSubclasses()
	{
		$this->assertSame([], $this->classReflection->getDirectSubclasses());
		$this->assertSame([], $this->classReflection->getIndirectSubclasses());
	}


	public function testInterfaces()
	{
		$this->assertSame([], $this->classReflection->getInterfaces());
		$this->assertSame([], $this->classReflection->getOwnInterfaces());
	}


	public function testSubclassOf()
	{
		$classReflectionMock = Mockery::mock(ApiGen\ElementReflection\PhpParser\ClassReflection::class);
		$classReflectionMock->shouldReceive('getName')->andReturn('SomeClass');
		$this->classReflection->isSubclassOf($classReflectionMock->getName());
	}


	public function testImplementsInterface()
	{
		$this->assertFalse($this->classReflection->implementsInterface('Countable'));
	}


	public function testGetExtension()
	{
		$extensionReflection = $this->classReflection->getExtension();
		$this->assertInstanceOf(ExtensionReflection::class, $extensionReflection);
	}

}
