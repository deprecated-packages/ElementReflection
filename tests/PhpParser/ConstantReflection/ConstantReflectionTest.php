<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ConstantReflection;

use ApiGen\ElementReflection\ParserInterface;
use ApiGen\ElementReflection\PhpParser\ConstantReflection;
use ApiGen\ElementReflection\PhpParser\Parser;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ContainerFactory;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use Nette\DI\Container;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class ConstantReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ConstantReflection
	 */
	private $constantReflection;

	/**
	 * @var ConstantReflection
	 */
	private $localConstantReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/ConstantReflectionSource');
		$this->constantReflection = $storage->getConstant(
			'ApiGen\ElementReflection\Tests\PhpParser\ConstantReflection\ConstantReflectionSource\SOME_CONSTANT'
		);

		$this->localConstantReflection = $storage->getConstant(
			'ApiGen\ElementReflection\Tests\PhpParser\ConstantReflection\ConstantReflectionSource\NAMESPACE_LOCAL'
		);
	}


	public function testNames()
	{
		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\ConstantReflection\ConstantReflectionSource\SOME_CONSTANT',
			$this->constantReflection->getName()
		);

		$this->assertSame(
			'ApiGen\ElementReflection\Tests\PhpParser\ConstantReflection\ConstantReflectionSource\SOME_CONSTANT',
			$this->constantReflection->getPrettyName()
		);

		$this->assertSame('SOME_CONSTANT', $this->constantReflection->getShortName());
	}


	public function testNamespace()
	{
		$this->assertTrue($this->constantReflection->inNamespace());
	}


	public function testValue()
	{
		$this->assertSame(5, $this->constantReflection->getValue());
		$this->assertNull($this->localConstantReflection->getValue());
	}

}
