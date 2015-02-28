<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection;

use ApiGen\ElementReflection\Parser\AnnotationParser;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\DocCommentSource\ClassDocInheritanceExplicitClass;


class DocCommentTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/DocCommentSource');
	}


	public function testDocCommentInheritance()
	{
		$classReflection = $this->storage->getClass(ClassDocInheritanceExplicitClass::class);
		$this->assertSame(
			'My Short description.',
			$classReflection->getAnnotation(AnnotationParser::SHORT_DESCRIPTION)
		);
		$this->assertSame(
			'Long description. Phew, that was long.',
			$classReflection->getAnnotation(AnnotationParser::LONG_DESCRIPTION)
		);
	}

}
