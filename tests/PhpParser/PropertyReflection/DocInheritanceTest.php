<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection;

use ApiGen\ElementReflection\Parser\AnnotationParser;
use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DocInheritanceSource\ParentClass;
use ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DocInheritanceSource\ChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DocInheritanceSource\GrandParentClass;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class PropertyReflectionDocInheritanceTest extends ParserAwareTestCase
{

	/**
	 * @var ClassReflection
	 */
	private $grandParentClassReflection;

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
		$storage = $this->parser->parseDirectory(__DIR__ . '/DocInheritanceSource');
		$this->grandParentClassReflection = $storage->getClass(GrandParentClass::class);
		$this->parentClassReflection = $storage->getClass(ParentClass::class);
		$this->childClassReflection = $storage->getClass(ChildClass::class);
	}


	public function testProperty1()
	{
		$parentProperty = $this->parentClassReflection->getProperty('param1');
		$childProperty = $this->childClassReflection->getProperty('param1');

		$this->assertSame($parentProperty->getAnnotations(), $childProperty->getAnnotations());

		$this->assertSame(
			'Private1 short. Protected1 short.',
			$childProperty->getAnnotation(AnnotationParser::SHORT_DESCRIPTION)
		);

		$this->assertSame(
			'Protected1 long. Private1 long.',
			$childProperty->getAnnotation(AnnotationParser::LONG_DESCRIPTION)
		);
	}


	public function testParam2()
	{
		$grandParentProperty = $this->grandParentClassReflection->getProperty('param2');
		$parentProperty = $this->parentClassReflection->getProperty('param2');
		$childProperty = $this->childClassReflection->getProperty('param2');

		$this->assertSame($parentProperty->getAnnotations(), $childProperty->getAnnotations());
		$this->assertSame($grandParentProperty->getAnnotations(), $childProperty->getAnnotations());
	}


	public function testParam3()
	{
		$childProperty = $this->childClassReflection->getProperty('param3');

		$this->assertSame(
			'Public3 Protected3  short.',
			$childProperty->getAnnotation(AnnotationParser::SHORT_DESCRIPTION)
		);
		$this->assertNull($childProperty->getAnnotation(AnnotationParser::LONG_DESCRIPTION));
	}


	public function testParam4()
	{
		$childParameter = $this->childClassReflection->getProperty('param4');

		$this->assertSame('Protected4 short.', $childParameter->getAnnotation(AnnotationParser::SHORT_DESCRIPTION));
		$this->assertNull($childParameter->getAnnotation(AnnotationParser::LONG_DESCRIPTION));
		$this->assertSame(['bool'], $childParameter->getAnnotation('var'));
	}

}
