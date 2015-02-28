<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Php\ExtensionReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class ExtensionReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ExtensionReflection
	 */
	private $internalReflectionExtension;


	protected function setUp()
	{
		parent::setUp();
		$this->internalReflectionExtension = new ExtensionReflection('phar', $this->parser->getStorage());
	}


	public function testName()
	{
		$this->assertSame('Phar', $this->internalReflectionExtension->getName());
	}


	public function testClasses()
	{
		$this->assertNull($this->internalReflectionExtension->getClass('...'));
		$this->assertCount(4, $this->internalReflectionExtension->getClasses());
	}


	public function testConstants()
	{
		$this->assertNull($this->internalReflectionExtension->getConstant('...'));
		$this->assertSame([], $this->internalReflectionExtension->getConstants());
	}


	public function testFunctions()
	{
		$this->assertNull($this->internalReflectionExtension->getFunction('...'));
		$this->assertSame([], $this->internalReflectionExtension->getFunctions());
	}

}
