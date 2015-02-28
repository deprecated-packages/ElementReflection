<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection;

use ApiGen\ElementReflection\PhpParser\InterfaceReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection\ParentsSource\OneClassWithInterface;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection\ParentsSource\ParentInterface;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection\ParentsSource\SecondClassWithInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class ParentsTest extends ParserAwareTestCase
{

	/**
	 * @var InterfaceReflection
	 */
	private $interfaceReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/ParentsSource');
		$this->interfaceReflection = $storage->getInterface(ParentInterface::class);
	}


	public function testDirectImplementers()
	{
		$directImplementers = $this->interfaceReflection->getDirectImplementers();

		$this->assertCount(1, $directImplementers);
		$this->assertArrayHasKey(OneClassWithInterface::class, $directImplementers);
	}


	public function testIndirectImplementers()
	{
		$indirectImplementers = $this->interfaceReflection->getIndirectImplementers();

		$this->assertCount(1, $indirectImplementers);
		$this->assertArrayHasKey(SecondClassWithInterface::class, $indirectImplementers);
	}

}
