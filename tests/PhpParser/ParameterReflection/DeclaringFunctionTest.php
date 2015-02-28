<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ParameterReflection;

use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\PhpParser\FunctionReflection;
use ApiGen\ElementReflection\PhpParser\ParameterReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionDeclaringFunctionSource\DeclaringMethod;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class DeclaringFunctionTest extends ParserAwareTestCase
{

	/**
	 * @var ParameterReflection
	 */
	private $functionParameterReflection;

	/**
	 * @var ParameterReflection
	 */
	private $methodParameterReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/DeclaringFunctionSource');

		$functionReflection = $storage->getFunction(
			'ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionDeclaringFunctionSource\declaringFunction'
		);
		$this->functionParameterReflection = $functionReflection->getParameter('someParameter');

		$classReflection = $storage->getClass(DeclaringMethod::class);
		$methodReflection = $classReflection->getMethod('declaringMethod');
		$this->methodParameterReflection = $methodReflection->getParameter('someParameter');
	}


	public function testDeclaringFunction()
	{
		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionDeclaringFunctionSource\declaringFunction',
			$this->functionParameterReflection->getDeclaringFunction()->getName()
		);
		$this->assertInstanceOf(FunctionReflection::class, $this->functionParameterReflection->getDeclaringFunction());
		$this->assertNull($this->functionParameterReflection->getDeclaringClass());
	}


	public function testDeclaringMethod()
	{
		$this->assertSame('someParameter', $this->methodParameterReflection->getName());
		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionDeclaringFunctionSource\DeclaringMethod',
			$this->methodParameterReflection->getDeclaringClass()->getName()
		);
		$this->assertInstanceOf(ClassReflection::class, $this->methodParameterReflection->getDeclaringClass());
	}

}
