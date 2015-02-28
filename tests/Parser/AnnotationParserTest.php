<?php

namespace ApiGen\ElementReflection\Tests\Parser;

use ApiGen\ElementReflection\Parser\AnnotationParser;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\Parser\AnnotationParserSource\AnnotationParserChildClass;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class AnnotationParserTest extends ParserAwareTestCase
{

	/**
	 * @var AnnotationParser
	 */
	private $annotationParser;

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->annotationParser = new AnnotationParser;
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/AnnotationParserSource');
	}


	public function testInheritForClass()
	{
		$classReflection = $this->storage->getClass(AnnotationParserChildClass::class);
		$annotations = $this->annotationParser->inheritForClass($classReflection, []);

		$this->assertSame(['short_description' => 'Some annotation'], $annotations);
	}


	public function testInheritForMethod()
	{
		$classReflection = $this->storage->getClass(AnnotationParserChildClass::class);
		$methodReflection = $classReflection->getMethod('someMethod');
		$annotations = $this->annotationParser->inheritForMethod($methodReflection, []);

		$this->assertSame(['short_description' => 'Method annotation'], $annotations);
	}

}
