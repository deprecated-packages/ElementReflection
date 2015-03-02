<?php

namespace ApiGen\ElementReflection\Magic;

use ApiGen\ElementReflection\Tests\Magic\MagicParameterReflectionSource\ClassWithMagicMethodsWithParameters;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class MagicParameterReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var MagicParameterReflection[]
	 */
	private $magicParameterReflections;


	protected function setUp()
	{
		parent::setUp();
		$storage = $this->parser->parseDirectory(__DIR__ . '/MagicParameterReflectionSource');
		$classReflection = $storage->getClass(ClassWithMagicMethodsWithParameters::class);
		$magicMethods = $classReflection->getMagicMethods();
		$this->magicParameterReflections = $magicMethods['getName']->getParameters();
	}


	public function testInstance()
	{
		$this->assertCount(2, $this->magicParameterReflections);
		$magicParameterReflection = $this->magicParameterReflections['firstParam'];
		$this->assertInstanceOf(MagicParameterReflection::class, $magicParameterReflection);

		$this->assertSame('firstParam', $magicParameterReflection->getName());
		$this->assertSame('5', $magicParameterReflection->getDefaultValue());
		$this->assertSame('FirstParam', $magicParameterReflection->getTypeHint());
	}

}
