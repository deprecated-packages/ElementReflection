<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ParameterReflection;

use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ParameterReflection\DocInheritanceSource\ChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\ParameterReflection\DocInheritanceSource\GrandParentClass;
use ApiGen\ElementReflection\Tests\PhpParser\ParameterReflection\DocInheritanceSource\ParentClass;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class DocInheritanceTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/DocInheritanceSource');
	}



	public function testDocCommentInheritance()
	{
		$grandParent = $this->storage->getClass(GrandParentClass::class)->getMethod('m');
		$parent = $this->storage->getClass(ParentClass::class)->getMethod('m');
		$childClass = $this->storage->getClass(ChildClass::class)->getMethod('m');

		$this->assertNotNull($grandParent);
		$this->assertNotNull($parent);
		$this->assertNotNull($childClass);

		$this->assertSame($grandParent->getAnnotation('param'), $parent->getAnnotation('param'));
		$this->assertSame(count($grandParent->getAnnotation('param')), count($childClass->getAnnotation('param')));
	}

}
