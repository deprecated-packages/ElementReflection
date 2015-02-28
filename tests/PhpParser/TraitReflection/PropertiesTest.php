<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\TraitReflection;

use ApiGen\ElementReflection\Parser;
use ApiGen\ElementReflection\PhpParser\TraitReflection;
use ApiGen\ElementReflection\PropertyReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\TraitReflection\PropertiesSource\PropertiesTrait;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class PropertiesTest extends ParserAwareTestCase
{

	/**
	 * @var TraitReflection
	 */
	private $traitReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/PropertiesSource');
		$this->traitReflection = $storage->getTrait(PropertiesTrait::class);
	}


	public function testOwnProperties()
	{
		$properties = $this->traitReflection->getOwnProperties();

		$this->assertCount(1, $properties);
		$this->assertArrayHasKey('someTraitProperty' , $properties);
		$this->traitReflection->hasOwnProperty('someTraitProperty');
	}


	public function testGetOnwProperty()
	{
		$property = $this->traitReflection->getProperty('someTraitProperty');
		$this->assertInstanceOf(PropertyReflectionInterface::class, $property);
	}


	public function testProperties()
	{
		$properties = $this->traitReflection->getProperties();

		$this->assertCount(2, $properties);
		$this->assertArrayHasKey('someTraitProperty' , $properties);
		$this->assertArrayHasKey('someOtherTraitProperty' , $properties);
		$this->traitReflection->hasProperty('someTraitProperty');
		$this->traitReflection->hasProperty('someOtherTraitProperty');
	}


	public function testGetProperty()
	{
		$property = $this->traitReflection->getProperty('someOtherTraitProperty');
		$this->assertInstanceOf(PropertyReflectionInterface::class, $property);
	}

}
