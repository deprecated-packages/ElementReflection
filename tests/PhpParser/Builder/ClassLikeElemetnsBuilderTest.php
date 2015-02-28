<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\Builder;

use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\Builder\ClassLikeElementsBuilderSource\SomeClass;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class ClassLikeElementsBuilderTest extends ParserAwareTestCase
{

	/**
	 * @var ClassReflection
	 */
	private $classReflection;


	protected function setUp()
	{
		parent::setUp();
		$storage = $this->parser->parseDirectory(__DIR__ . '/ClassLikeElementsBuilderSource');
		$this->classReflection = $storage->getClass(SomeClass::class);
	}


	public function testConstants()
	{
		$constants = $this->classReflection->getConstants();
		$this->assertCount(1, $constants);
	}


	public function testProperties()
	{
		$properties = $this->classReflection->getProperties();
		$this->assertCount(1, $properties);
	}


	public function testMethods()
	{
		$methods = $this->classReflection->getMethods();
		$this->assertCount(1, $methods);
	}

}
