<?php

namespace ApiGen\ElementReflection\Tests\PhpParser;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\PhpParser\PropertyReflection;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\PropertyReflectionSource\SomeClass;
use ApiGen\ElementReflection\Tests\PhpParser\PropertyReflectionSource\SomeTrait;
use ApiGen\ElementReflection\TraitReflectionInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class PropertyReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var PropertyReflection
	 */
	private $propertyReflection;

	/**
	 * @var PropertyReflection
	 */
	private $traitPropertyReflection;


	protected function setUp()
	{
		parent::setUp();
		$storage = $this->parser->parseDirectory(__DIR__ . '/PropertyReflectionSource');
		$classReflection = $storage->getClass('ApiGen\ElementReflection\Tests\PhpParser\PropertyReflectionSource\SomeClass');

		$this->propertyReflection = $classReflection->getProperty('someProperty');

		$traitReflection = $storage->getTrait('ApiGen\ElementReflection\Tests\PhpParser\PropertyReflectionSource\SomeTrait');
		$this->traitPropertyReflection = $traitReflection->getProperty('someProperty');
	}


	public function testNames()
	{
		$this->assertSame('someProperty', $this->propertyReflection->getName());
	}


	public function testTypes()
	{
		$this->assertTrue($this->propertyReflection->isPublic());
		$this->assertFalse($this->propertyReflection->isProtected());
		$this->assertFalse($this->propertyReflection->isPrivate());
		$this->assertTrue($this->propertyReflection->isStatic());
	}


	public function testDeclaringClass()
	{
		$declaringClass = $this->propertyReflection->getDeclaringClass();
		$this->assertInstanceOf(ClassReflectionInterface::class, $declaringClass);
		$this->assertSame(SomeClass::class, $declaringClass->getName());
	}


	public function testDeclaringTrait()
	{
		$declaringTrait = $this->traitPropertyReflection->getDeclaringTrait();
		$this->assertInstanceOf(TraitReflectionInterface::class, $declaringTrait);
		$this->assertSame(SomeTrait::class, $declaringTrait->getName());
	}


	public function testDefaultValue()
	{
		$this->assertNull($this->propertyReflection->getDefaultValue());
		$this->assertSame(5, $this->traitPropertyReflection->getDefaultValue());
	}


	public function testPrettyName()
	{
		$this->assertSame(SomeClass::class . '::$someProperty', $this->propertyReflection->getPrettyName());
	}

}
