<?php

namespace ApiGen\ElementReflection\Tests\PhpParser;

use ApiGen\ElementReflection\Parser;
use ApiGen\ElementReflection\ParserInterface;
use ApiGen\ElementReflection\PhpParser\AbstractReflection;
use ApiGen\ElementReflection\PropertyReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ContainerFactory;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource\SomeClass;
use Nette\DI\Container;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PHPUnit_Framework_TestCase;


class AbstractReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var AbstractReflection
	 */
	private $abstractReflection;

	/**
	 * @var AbstractReflection|PropertyReflectionInterface
	 */
	private $abstractPropertyReflection;


	protected function setUp()
	{
		parent::setUp();

		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/ClassReflection/ClassReflectionSource');
		$this->abstractReflection = $storage->getClass(SomeClass::class);

		$this->abstractPropertyReflection = $this->abstractReflection->getProperty('someProperty');
	}


	public function testNamespace()
	{
		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource',
			$this->abstractReflection->getNamespaceName()
		);

		$this->assertTrue($this->abstractReflection->inNamespace());
	}


	public function testStartLineEndLine()
	{
		$this->assertSame(14, $this->abstractReflection->getStartLine());
		$this->assertSame(31, $this->abstractReflection->getEndLine());
	}


	public function testIsDeprecated()
	{
		$this->assertTrue($this->abstractReflection->isDeprecated());
	}


//	public function testInheritDocCommentForProperty()
//	{
//		$docComment = <<<DOC
///**
//	 * Inherited doc
//	 */
//DOC;
//
//		$this->assertSame($docComment, $this->abstractPropertyReflection->getDocComment());
//	}


	public function testAnnotations()
	{
		$this->assertTrue($this->abstractReflection->hasAnnotation('deprecated'));
		$this->assertFalse($this->abstractReflection->hasAnnotation('author'));

		$this->assertSame([''], $this->abstractReflection->getAnnotation('deprecated'));

		$this->assertSame([
				'short_description' => 'Some short description.',
				'long_description' => 'Some long description.',
				'deprecated' => ['']
			], $this->abstractReflection->getAnnotations()
		);
	}

}
