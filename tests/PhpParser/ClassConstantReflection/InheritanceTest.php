<?php

namespace ApiGen\ElementReflection\Tests\PhpParser;

use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassConstantReflection\InheritanceSource\ConstantInterfaceClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassConstantReflection\InheritanceSource\ConstantInterfaceClass2;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class InheritanceTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/InheritanceSource');
	}


	public function testClassConstantInheritance()
	{
		$class1 = $this->storage->getClass(ConstantInterfaceClass::class);

		$this->assertTrue($class1->hasConstant('FIRST'));

		$class2 = $this->storage->getClass(ConstantInterfaceClass2::class);

		$this->assertTrue($class2->hasConstant('FIRST'));
		$this->assertTrue($class2->hasConstant('SECOND'));
	}

}
