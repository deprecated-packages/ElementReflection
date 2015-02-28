<?php

namespace ApiGen\ElementReflection\Tests\Storage;

use ApiGen;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class MemoryStorageNonExistingTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__);
	}


	/**
	 * @expectedException ApiGen\ElementReflection\Exception\NonExistingElementException
	 */
	public function testGetNonExistingConstant()
	{
		$this->storage->getConstant('nonExisting');
	}


	/**
	 * @expectedException ApiGen\ElementReflection\Exception\NonExistingElementException
	 */
	public function testGetNonExistingFunction()
	{
		$this->storage->getFunction('nonExisting');
	}


	/**
	 * @expectedException ApiGen\ElementReflection\Exception\NonExistingElementException
	 */
	public function testGetNonExistingInterface()
	{
		$this->storage->getInterface('nonExisting');
	}


	/**
	 * @expectedException ApiGen\ElementReflection\Exception\NonExistingElementException
	 */
	public function testGetNonExistingTrait()
	{
		$this->storage->getTrait('nonExisting');
	}

}
