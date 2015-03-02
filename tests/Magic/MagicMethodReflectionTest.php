<?php

namespace ApiGen\ElementReflection\Magic;

use ApiGen\ElementReflection\Parser\AnnotationParser;
use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\Tests\Magic\MagicMethodReflectionSource\ClassWithMagicMethods;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class MagicMethodReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var MagicMethodReflection[]
	 */
	private $magicMethods;


	protected function setUp()
	{
		parent::setUp();
		$storage = $this->parser->parseDirectory(__DIR__ . '/MagicMethodReflectionSource');
		$classReflection = $storage->getClass(ClassWithMagicMethods::class);
		$this->magicMethods = $classReflection->getMagicMethods();
	}


	public function testMagicMethods()
	{
		$this->assertCount(1, $this->magicMethods);
		$this->assertArrayHasKey('getName', $this->magicMethods);

		/** @var MagicMethodReflection $magicMethod */
		$magicMethod = $this->magicMethods['getName'];
		$this->assertInstanceOf(MagicMethodReflection::class, $magicMethod);

		$this->assertSame('getName', $magicMethod->getName());
		$this->assertFalse($magicMethod->returnsReference());

		$this->assertSame(10, $magicMethod->getStartLine());
		$this->assertSame(10, $magicMethod->getEndLine());
	}


	public function testDeclaringClass()
	{
		$magicMethod = $this->magicMethods['getName'];
		$declaringClass = $magicMethod->getDeclaringClass();
		$this->assertInstanceOf(ClassReflection::class, $declaringClass);
		$this->assertSame(ClassWithMagicMethods::class, $declaringClass->getName());
	}


	public function testAnnotations()
	{
		$magicMethod = $this->magicMethods['getName'];
		$this->assertSame('Some description.', $magicMethod->getAnnotation(AnnotationParser::SHORT_DESCRIPTION));
		$this->assertSame(['ReturnType'], $magicMethod->getAnnotation('return'));
	}


	public function testParameters()
	{
		$magicMethod = $this->magicMethods['getName'];
		$parameters = $magicMethod->getParameters();
		$this->assertCount(0, $parameters);
	}

}
