<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection;

use ApiGen\ElementReflection\PropertyReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\PropertiesSource\ClassDoublePropertiesChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\PropertiesSource\ClassPropertiesChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\PropertiesSource\NoPropertiesClass;


class TraitsTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/PropertiesSource');
	}


	public function testProperties()
	{
		$classReflection = $this->storage->getClass(ClassPropertiesChildClass::class);
		$properties = ['public', 'publicStatic', 'protected', 'protectedStatic', 'private', 'privateStatic'];
		foreach ($properties as $property) {
			$this->assertTrue($classReflection->hasProperty($property));
			$this->assertInstanceOf(PropertyReflectionInterface::class, $classReflection->getProperty($property));
		}

		$this->assertFalse($classReflection->hasProperty('nonExistent'));
	}


	public function testOwnProperties()
	{
		$classReflection = $this->storage->getClass(ClassPropertiesChildClass::class);

		$properties = ['public', 'publicStatic', 'private', 'privateStatic'];
		foreach ($properties as $property) {
			$this->assertTrue($classReflection->hasOwnProperty($property));
		}

		$properties = ['protectedStatic', 'protectedStatic'];
		foreach ($properties as $property) {
			$this->assertFalse($classReflection->hasOwnProperty($property));
		}
	}


	public function testNoProperties()
	{
		$classReflection = $this->storage->getClass(NoPropertiesClass::class);

		$this->assertSame([], $classReflection->getProperties());
		$this->assertSame([], $classReflection->getOwnProperties());

		$this->assertFalse($classReflection->hasProperty('nonExistent'));
		$this->assertFalse($classReflection->hasOwnProperty('nonExistent'));
	}


	public function testDoubleProperties()
	{
		$classReflection = $this->storage->getClass(ClassDoublePropertiesChildClass::class);
		$properties = ['publicOne', 'publicTwo', 'protectedOne', 'protectedTwo', 'privateOne', 'privateTwo'];
		foreach ($properties as $property) {
			$this->assertTrue($classReflection->hasProperty($property));
			$this->assertInstanceOf(PropertyReflectionInterface::class, $classReflection->getProperty($property));
		}
	}

}
