<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection;

use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource\SomeClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource\SomeParentClass;


class ClassReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ClassReflection
	 */
	private $classReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/ClassReflectionSource');
		$this->classReflection = $storage->getClass(SomeClass::class);
	}


	public function testNames()
	{
		$this->assertSame(SomeClass::class, $this->classReflection->getName());
		$this->assertSame('SomeClass', $this->classReflection->getShortName());

		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource',
			$this->classReflection->getNamespaceName()
		);
		$this->assertTrue($this->classReflection->inNamespace());
	}


	public function testModifiers()
	{
		$this->assertFalse($this->classReflection->isAbstract());
		$this->assertFalse($this->classReflection->isFinal());
		$this->assertFalse($this->classReflection->isException());
		$this->assertFalse($this->classReflection->isInterface());
	}


	public function testParents()
	{
		$this->assertSame('SomeParentClass', $this->classReflection->getParentClass()->getShortName());

		$this->assertSame(
			[SomeParentClass::class => SomeParentClass::class],
			$this->classReflection->getParentClassNameList()
		);

		$this->assertCount(1, $this->classReflection->getParentClasses());
		$this->assertArrayHasKey(SomeParentClass::class, $this->classReflection->getParentClasses());
	}


	public function testInterfaces()
	{
		$this->assertTrue($this->classReflection->implementsInterface('Countable'));
		$this->assertFalse($this->classReflection->implementsInterface('Traversable'));

		$this->assertCount(2, $this->classReflection->getInterfaces());
		$this->assertArrayHasKey('Countable', $this->classReflection->getInterfaces());
		$this->assertArrayHasKey('Serializable', $this->classReflection->getInterfaces());

		$this->assertCount(1, $this->classReflection->getOwnInterfaces());
		$this->assertArrayHasKey('Countable', $this->classReflection->getOwnInterfaces());
	}

}
