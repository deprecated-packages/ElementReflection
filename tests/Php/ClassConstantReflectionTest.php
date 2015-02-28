<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Php\ClassConstantReflection;
use ApiGen\ElementReflection\Php\ClassReflection;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class ClassConstantReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ClassConstantReflection
	 */
	private $classConstantReflection;


	protected function setUp()
	{
		parent::setUp();
		$this->classConstantReflection = $this->parser->getStorage()->getClass('Phar')->getConstant('SHA1');
	}


	public function testName()
	{
		$this->assertSame('SHA1', $this->classConstantReflection->getName());
		$this->assertSame('Phar::SHA1', $this->classConstantReflection->getPrettyName());
	}


	public function testGetDeclaringClass()
	{
		$declaringClass = $this->classConstantReflection->getDeclaringClass();
		$this->assertInstanceOf(ClassReflection::class, $declaringClass);
		$this->assertSame('Phar', $declaringClass->getName());
	}

}
