<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection;

use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DefaultSource\ChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DefaultSource\ParentClass;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class DefaultTest extends ParserAwareTestCase
{

	/**
	 * @var ClassReflection
	 */
	private $parentClassReflection;

	/**
	 * @var ClassReflection
	 */
	private $childClassReflection;


	protected function setUp()
	{
		parent::setUp();

		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/DefaultSource');
		$this->parentClassReflection = $storage->getClass(ParentClass::class);
		$this->childClassReflection = $storage->getClass(ChildClass::class);
	}


	public function testParentClassProperties()
	{
		$class = $this->parentClassReflection;

		$this->assertTrue($class->hasProperty('default'));
		$property = $class->getProperty('default');
		$this->assertSame('default', $property->getDefaultValue());

		$this->assertTrue($class->hasProperty('default2'));
		$property = $class->getProperty('default2');
		$this->assertSame('default', $property->getDefaultValue());

		$this->assertTrue($class->hasProperty('default3'));
		$property = $class->getProperty('default3');
		$this->assertSame('default', $property->getDefaultValue());
	}


	public function testPropertiesInheritance()
	{
		$class = $this->childClassReflection;

		$this->assertTrue($class->hasProperty('default4'));
		$property = $class->getProperty('default4');
		$this->assertSame('not default', $property->getDefaultValue());

		$this->assertTrue($class->hasProperty('default5'));
		$property = $class->getProperty('default5');
		$this->assertSame('not default', $property->getDefaultValue());

		$this->assertTrue($class->hasProperty('default6'));
		$property = $class->getProperty('default6');
		$this->assertSame('default', $property->getDefaultValue());

		$this->assertTrue($class->hasProperty('default7'));
		$property = $class->getProperty('default7');
		$this->assertSame('default', $property->getDefaultValue());

		$property = $class->getProperty('default8');
		$this->assertTrue($class->hasProperty('default8'));
		$this->assertSame('default', $property->getDefaultValue());

		$this->assertTrue($class->hasProperty('default9'));
		$property = $class->getProperty('default9');
		$this->assertSame(['not default', 'default', 'default'], $property->getDefaultValue());
	}

}
