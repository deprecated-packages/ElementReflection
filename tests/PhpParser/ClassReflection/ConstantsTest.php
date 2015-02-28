<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection;

use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource\ConstantsChildren;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource\NoConstants;
use FilesystemIterator;
use RecursiveDirectoryIterator;


class ConstantsTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/ClassReflectionSource');
	}


	public function testConstants()
	{
		$classReflection = $this->storage->getClass(ConstantsChildren::class);

		$this->assertTrue($classReflection->hasConstant('STRING'));
		$this->assertTrue($classReflection->hasOwnConstant('STRING'));
		$this->assertFalse($classReflection->hasConstant('NONEXISTENT'));
		$this->assertFalse($classReflection->hasOwnConstant('NONEXISTENT'));
		$this->assertFalse($classReflection->hasOwnConstant('PARENT'));

		$this->assertCount(5, $classReflection->getConstants());
		$this->assertCount(4, $classReflection->getOwnConstants());
	}


	public function testNoConstants()
	{
		$classReflection = $this->storage->getClass(NoConstants::class);

		$this->assertFalse($classReflection->hasConstant('NONEXISTENT'));
		$this->assertFalse($classReflection->hasOwnConstant('NONEXISTENT'));

		$this->assertSame([], $classReflection->getConstants());
		$this->assertSame([], $classReflection->getOwnConstants());
	}


	public function testInternalClass()
	{
		$classReflection = $this->storage->getClass(RecursiveDirectoryIterator::class);

		$this->assertTrue($classReflection->hasConstant('CURRENT_AS_PATHNAME'));
		$this->assertFalse($classReflection->hasOwnConstant('CURRENT_AS_PATHNAME'));
		$this->assertCount(0, $classReflection->getOwnConstants());

		$this->assertSame(
			FilesystemIterator::class,
			$classReflection->getConstant('CURRENT_AS_PATHNAME')->getDeclaringClass()->getName()
		);
	}

}
