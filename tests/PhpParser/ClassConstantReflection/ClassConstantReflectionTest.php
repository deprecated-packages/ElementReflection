<?php

namespace ApiGen\ElementReflection\Tests\PhpParser;

use ApiGen\ElementReflection\PhpParser\ClassConstantReflection;
use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassConstantReflection\ClassConstantReflectionSource\SomeConstantInClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassConstantReflection\ClassConstantReflectionSource\SomeConstantInClassOverriding;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class ClassConstantReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ClassConstantReflection
	 */
	private $classConstantReflection;

	/**
	 * @var ClassConstantReflection
	 */
	private $classConstantOverridingReflection;


	protected function setUp()
	{
		parent::setUp();
		$storage = $this->parser->parseDirectory(__DIR__ . '/ClassConstantReflectionSource');

		/** @var ClassReflection $classWithConstant */
		$classWithConstant = $storage->getClass(SomeConstantInClass::class);
		$this->classConstantReflection = $classWithConstant->getConstant('SOME_CONSTANT');

		/** @var ClassReflection $classWithConstant */
		$classWithConstantOverriding = $storage->getClass(SomeConstantInClassOverriding::class);
		$this->classConstantOverridingReflection = $classWithConstantOverriding->getConstant('SOME_CONSTANT');
	}


	public function testNames()
	{
		$this->assertSame(SomeConstantInClass::class . '::SOME_CONSTANT', $this->classConstantReflection->getName());

		$this->assertSame(
			SomeConstantInClass::class . '::SOME_CONSTANT',
			$this->classConstantReflection->getPrettyName()
		);

		$this->assertSame('SOME_CONSTANT', $this->classConstantReflection->getShortName());
	}


	public function testDeclaringClass()
	{
		$declaringClass = $this->classConstantReflection->getDeclaringClass();
		$this->assertSame(SomeConstantInClass::class, $declaringClass->getName());
	}


	public function testValue()
	{
		$this->assertSame(5, $this->classConstantReflection->getValue());
		$this->assertSame(10, $this->classConstantOverridingReflection->getValue());
	}

}
