<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ParameterReflection;

use ApiGen\ElementReflection\MethodReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionDefaultValuesByConstantSource\ConstantValue;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class DefaultValuesByConstantTest extends ParserAwareTestCase
{

	/**
	 * @var MethodReflectionInterface
	 */
	private $methodReflection;


	protected function setUp()
	{
		parent::setUp();

		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/DefaultValuesByConstantSource');

		$classReflection = $storage->getClass(ConstantValue::class);
		$this->methodReflection = $classReflection->getMethod('constantValue');
	}


	public function test()
	{
		$expected = [
			'one' => [FALSE, NULL, 'foo'],
			'two' => [FALSE, NULL, 'bar'],
			'three' => [TRUE, 'self::VALUE', 'bar'],
			'four' => [TRUE, 'ElementReflection_Test_ParameterConstantValue::VALUE', 'bar'],
			'five' => [TRUE, 'TOKEN_REFLECTION_PARAMETER_CONSTANT_VALUE', 'foo']
		];

		$this->assertCount(5, $this->methodReflection->getParameters());

		foreach ($this->methodReflection->getParameters() as $parameter) {
			$this->assertTrue(isset($expected[$parameter->getName()]));
			list($isConstant, $constantName, $value) = $expected[$parameter->getName()];
			$this->assertSame($value, $parameter->getDefaultValue());
		}
	}

}
