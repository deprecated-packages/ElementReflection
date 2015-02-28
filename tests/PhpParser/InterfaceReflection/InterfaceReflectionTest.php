<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflection;

use ApiGen\ElementReflection\PhpParser\InterfaceReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\InterfaceReflectionSource\SomeInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class InterfaceReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var InterfaceReflection
	 */
	private $interfaceReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/InterfaceReflectionSource');
		$this->interfaceReflection = $storage->getInterface(SomeInterface::class);
	}


	public function testNames()
	{
		$this->assertSame(SomeInterface::class, $this->interfaceReflection->getName());
	}


	public function testImplements()
	{
		$this->assertTrue($this->interfaceReflection->implementsInterface('Countable'));
		$this->assertFalse($this->interfaceReflection->implementsInterface('ArrayAccess'));
	}

}
