<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\Php\ClassReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ParentsSource\ClassParentsChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ParentsSource\ClassParentsGrandParentClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ParentsSource\ClassParentsParentClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ParentsSource\NoParentsClass;
use Exception;
use ReflectionClass;


class ParentsTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/ParentsSource');
	}


	public function testIsSubclassOf()
	{
		$classReflection = $this->storage->getClass(ClassParentsChildClass::class);
		$this->assertTrue($classReflection->isSubclassOf(ClassParentsGrandParentClass::class));
		$this->assertTrue($classReflection->isSubclassOf(ClassParentsParentClass::class));
		$this->assertFalse($classReflection->isSubclassOf(ClassParentsChildClass::class));
		$this->assertFalse($classReflection->isSubclassOf(Exception::class));

		$parentClassReflection = $this->storage->getClass(ClassParentsParentClass::class);
		$this->assertTrue($parentClassReflection->isSubclassOf(ClassParentsGrandParentClass::class));
	}


	public function testGetParentClass()
	{
		$classReflection = $this->storage->getClass(ClassParentsChildClass::class);

		$this->assertInstanceOf(ClassReflectionInterface::class, $classReflection->getParentClass());
		$this->assertSame(ClassParentsParentClass::class, $classReflection->getParentClass()->getName());
	}


	public function testGetParentClasses()
	{
		$classReflection = $this->storage->getClass(ClassParentsChildClass::class);
		$this->assertSame(3, count($classReflection->getParentClasses()));

		$this->assertInstanceOf(
			ClassReflectionInterface::class,
			$classReflection->getParentClasses()[ClassParentsParentClass::class]
		);
		$this->assertInstanceOf(
			ClassReflectionInterface::class,
			$classReflection->getParentClasses()[ClassParentsGrandParentClass::class]
		);
		$this->assertInstanceOf(
			ClassReflection::class,
			$classReflection->getParentClasses()[ReflectionClass::class]
		);

		$this->assertSame([
			ClassParentsParentClass::class => ClassParentsParentClass::class,
			ClassParentsGrandParentClass::class => ClassParentsGrandParentClass::class,
			ReflectionClass::class => ReflectionClass::class
		], $classReflection->getParentClassNameList());
	}


	public function testNoParentsClasses()
	{
		$classReflection = $this->storage->getClass(NoParentsClass::class);

		$this->assertFalse($classReflection->isSubclassOf('Exception'));
		$this->assertSame([], $classReflection->getParentClasses());
		$this->assertSame([], $classReflection->getParentClassNameList());
	}


	public function testDirectSubclasses()
	{
		$classReflection = $this->storage->getClass(ClassParentsGrandParentClass::class);
		$subclasses = $classReflection->getDirectSubclasses();

		$this->assertCount(1, $subclasses);
		$this->assertArrayHasKey(ClassParentsParentClass::class, $subclasses);
	}


	public function testIndirectSubclasses()
	{
		$classReflection = $this->storage->getClass(ClassParentsGrandParentClass::class);
		$subclasses = $classReflection->getIndirectSubclasses();

		$this->assertCount(1, $subclasses);
		$this->assertArrayHasKey(ClassParentsChildClass::class, $subclasses);
	}

}
