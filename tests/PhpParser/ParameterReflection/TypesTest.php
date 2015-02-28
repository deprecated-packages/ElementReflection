<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ParameterReflection;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\Php\ClassReflection;
use ApiGen\ElementReflection\PhpParser\FunctionReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class TypesTest extends ParserAwareTestCase
{

	/**
	 * @var FunctionReflection
	 */
	private $functionReflection;


	protected function setUp()
	{
		parent::setUp();

		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/ParameterReflectionSource');

		$this->functionReflection = $storage->getFunction(
			'ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionSource\types'
		);
	}


	public function testArray()
	{
		$this->assertTrue($this->functionReflection->getParameter('array')->isArray());
		$this->assertTrue($this->functionReflection->getParameter('anotherArray')->isArray());
	}


	public function testTypes()
	{
		$parameter = $this->functionReflection->getParameter('exception');
		$this->assertSame('Exception', $parameter->getClass()->getName());
		$this->assertInstanceOf(ClassReflection::class, $parameter->getClass());
	}

}
