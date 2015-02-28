<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\MethodReflection;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\Parser\AnnotationParser;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\MethodReflection\DocInheritanceSource\ChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\MethodReflection\DocInheritanceSource\GrandParentClass;
use ApiGen\ElementReflection\Tests\PhpParser\MethodReflection\DocInheritanceSource\ParentClass;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class DocInheritanceTest extends ParserAwareTestCase
{

	/**
	 * @var ClassReflectionInterface
	 */
	private $childClassReflection;

	/**
	 * @var ClassReflectionInterface
	 */
	private $parentClassReflection;

	/**
	 * @var ClassReflectionInterface
	 */
	private $grandParentClassReflection;



	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/DocInheritanceSource');

		$this->childClassReflection = $storage->getClass(ChildClass::class);
		$this->parentClassReflection = $storage->getClass(ParentClass::class);
		$this->grandParentClassReflection = $storage->getClass(GrandParentClass::class);
	}


	public function testDocInheritance()
	{
		$grandParent = $this->grandParentClassReflection;
		$parent = $this->parentClassReflection;
		$child = $this->childClassReflection;

		$this->assertSame($parent->getMethod('method1')->getAnnotations(), $child->getMethod('method1')->getAnnotations());
		$this->assertSame('Private1 short. Protected1 short.', $child->getMethod('method1')->getAnnotation(AnnotationParser::SHORT_DESCRIPTION));
		$this->assertSame('Protected1 long. Private1 long.', $child->getMethod('method1')->getAnnotation(AnnotationParser::LONG_DESCRIPTION));

		$this->assertSame($parent->getMethod('method2')->getAnnotations(), $child->getMethod('method2')->getAnnotations());
		$this->assertSame($grandParent->getMethod('method2')->getAnnotations(), $child->getMethod('method2')->getAnnotations());

		$this->assertSame('Public3 Protected3  short.', $child->getMethod('method3')->getAnnotation(AnnotationParser::SHORT_DESCRIPTION));
		$this->assertNull($child->getMethod('method3')->getAnnotation(AnnotationParser::LONG_DESCRIPTION));

		$this->assertSame([], $child->getMethod('method4')->getAnnotations());
		$this->assertNull($child->getMethod('method4')->getAnnotation(AnnotationParser::LONG_DESCRIPTION));

		$this->assertSame($grandParent->getMethod('method1')->getAnnotation('throws'), $parent->getMethod('method1')->getAnnotation('throws'));
		$this->assertSame($grandParent->getMethod('method1')->getAnnotation('throws'), $child->getMethod('method1')->getAnnotation('throws'));
		$this->assertSame(['Exception'], $grandParent->getMethod('method1')->getAnnotation('throws'));
		$this->assertSame(['string'], $parent->getMethod('method1')->getAnnotation('return'));

		$this->assertSame($grandParent->getMethod('method2')->getAnnotation('return'), $parent->getMethod('method2')->getAnnotation('return'));
		$this->assertSame($parent->getMethod('method2')->getAnnotation('return'), $child->getMethod('method2')->getAnnotation('return'));
		$this->assertSame(['mixed'], $parent->getMethod('method2')->getAnnotation('return'));

		$this->assertSame($parent->getMethod('method3')->getAnnotation('return'), $child->getMethod('method3')->getAnnotation('return'));
		$this->assertSame(['bool'], $child->getMethod('method3')->getAnnotation('return'));
	}

}
