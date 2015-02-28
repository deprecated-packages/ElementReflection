<?php

namespace ApiGen\ElementReflection\Tests\Storage;

use ApiGen;
use ApiGen\ElementReflection\Parser\ParserInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ContainerFactory;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;


class MemoryStorageTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var StorageInterface
	 */
	private $storage;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	protected function setUp()
	{
		/** @var ParserInterface $parser */
		$parser = $this->container->getByType(ParserInterface::class);
		$this->storage = $parser->parseDirectory(__DIR__);
	}


	public function testClasses()
	{
		$this->assertFalse($this->storage->hasClass('...'));
		$this->assertFalse($this->storage->hasClass('ns\\...'));
		$this->assertFalse($this->storage->hasClass('nonExistingNamespace\\...'));

		$this->assertGreaterThanOrEqual(40, $this->storage->getClasses());
	}


	public function testInternalClasses()
	{
		$internalClasses = $this->storage->getInternalClasses();
		$this->assertCount(5, $internalClasses);
	}


	public function testConstants()
	{
		$this->assertFalse($this->storage->hasConstant('SomeClass::someConstant'));
		$this->assertCount(0, $this->storage->getConstants());
	}


	public function testFunctions()
	{
		$this->assertFalse($this->storage->hasFunction('...'));
		$this->assertCount(0, $this->storage->getFunctions());
	}


	public function testInterfaces()
	{
		$this->assertFalse($this->storage->hasInterface('nonExistingInterface'));
		$this->assertCount(0, $this->storage->getInterfaces());
	}


	public function testTraits()
	{
		$this->assertFalse($this->storage->hasTrait('nonExistingTrait'));
		$this->assertCount(0, $this->storage->getTraits());
	}

}
