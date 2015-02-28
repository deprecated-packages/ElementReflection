<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ParameterReflection;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\FunctionReflectionInterface;
use ApiGen\ElementReflection\PhpParser\ParameterReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionSource\SomeClass;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class ParameterReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ParameterReflection
	 */
	private $parameterReflection;

	/**
	 * @var ParameterReflection
	 */
	private $classParameterReflection;



	protected function setUp()
	{
		parent::setUp();

		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/ParameterReflectionSource');
		$functionReflection = $storage->getFunction(
			'ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionSource\someFunction'
		);
		$this->parameterReflection = $functionReflection->getParameter('someParameter');

		$this->classParameterReflection = $storage->getClass(SomeClass::class)
			->getMethod('someMethod')
			->getParameter('someParameter');
	}


	public function testNames()
	{
		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionSource\someFunction($someParameter)',
			$this->parameterReflection->getPrettyName()
		);
	}


	public function testParent()
	{
		$functionReflection = $this->parameterReflection->getDeclaringFunction();
		$this->assertInstanceOf(FunctionReflectionInterface::class, $functionReflection);

		$this->assertNull($this->parameterReflection->getDeclaringClass());
	}


	public function testMethodParent()
	{
		$this->assertInstanceOf(ClassReflectionInterface::class, $this->classParameterReflection->getDeclaringClass());
	}


	public function testDefault()
	{
		$defaultValue = $this->parameterReflection->getDefaultValue();
		$this->assertSame(5, $defaultValue);
	}


	public function testType()
	{
		$this->assertFalse($this->parameterReflection->isArray());
		$this->assertTrue($this->parameterReflection->isOptional());
		$this->assertNull($this->classParameterReflection->getClass()); // missing class
	}


	public function testReference()
	{
		$this->assertFalse($this->parameterReflection->isPassedByReference());
	}

}
