<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\FunctionReflection;

use ApiGen\ElementReflection\ParameterReflectionInterface;
use ApiGen\ElementReflection\PhpParser\FunctionReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class FunctionReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var FunctionReflection
	 */
	private $functionReflection;

	/**
	 * @var FunctionReflection
	 */
	private $functionReflectionWithReference;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/FunctionReflectionSource');
		$this->functionReflection = $storage->getFunction(
			'ApiGen\ElementReflection\Tests\PhpParser\FunctionReflection\FunctionReflectionSource\someFunction'
		);

		$this->functionReflectionWithReference = $storage->getFunction(
			'ApiGen\ElementReflection\Tests\PhpParser\FunctionReflection\FunctionReflectionSource\someFunctionWithReference'
		);
	}


	public function testNames()
	{
		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\FunctionReflection\FunctionReflectionSource\someFunction',
			$this->functionReflection->getName()
		);

		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\FunctionReflection\FunctionReflectionSource\someFunction()',
			$this->functionReflection->getPrettyName()
		);
	}


	public function testReturnsReference()
	{
		$this->assertFalse($this->functionReflection->returnsReference());
		$this->assertTrue($this->functionReflectionWithReference->returnsReference());
	}


	public function testIsVariadic()
	{
		$this->assertTrue($this->functionReflection->isVariadic());
	}


	public function testParameters()
	{
		$parameters = $this->functionReflection->getParameters();
		$this->assertCount(2, $parameters);
		$this->assertArrayHasKey('someRefParameter', $parameters);
		$this->assertArrayHasKey('variadic', $parameters);

		$parameter = $this->functionReflection->getParameter(1);
		$this->assertInstanceOf(ParameterReflectionInterface::class, $parameter);

		$parameter2 = $this->functionReflection->getParameter('variadic');
		$this->assertSame($parameter, $parameter2);
	}

}
