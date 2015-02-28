<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection;

use ApiGen\ElementReflection\PhpParser\ClassConstantReflection;
use ApiGen\ElementReflection\PhpParser\ConstantReflection;
use ApiGen\ElementReflection\PhpParser\InterfaceReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection\ConstantsSource\ConstantsInterface;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection\ParentsSource\OneClassWithInterface;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection\ParentsSource\ParentInterface;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection\ParentsSource\SecondClassWithInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class ConstantsTest extends ParserAwareTestCase
{

	/**
	 * @var InterfaceReflection
	 */
	private $interfaceReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/ConstantsSource');
		$this->interfaceReflection = $storage->getInterface(ConstantsInterface::class);
	}


	public function testConstants()
	{
		$constants = $this->interfaceReflection->getConstants();
		$this->assertCount(2, $constants);
		$this->assertArrayHasKey('SOME_CONSTANT', $constants);
		$this->assertArrayHasKey('SOME_PARENT_CONSTANT', $constants);

		$this->assertTrue($this->interfaceReflection->hasConstant('SOME_CONSTANT'));
		$this->assertInstanceOf(
			ClassConstantReflection::class,
			$this->interfaceReflection->getConstant('SOME_CONSTANT')
		);

		$this->assertFalse($this->interfaceReflection->getConstant('SOME_NONEXISTING_CONSTANT'));
	}


	public function testOwnConstants()
	{
		$ownConstants = $this->interfaceReflection->getOwnConstants();
		$this->assertCount(1, $ownConstants);
		$this->assertArrayHasKey('SOME_CONSTANT', $ownConstants);

		$this->assertTrue($this->interfaceReflection->hasOwnConstant('SOME_CONSTANT'));
	}

}
