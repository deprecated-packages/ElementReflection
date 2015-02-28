<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\TraitReflection;

use ApiGen\ElementReflection\Parser;
use ApiGen\ElementReflection\PhpParser\TraitReflection;
use ApiGen\ElementReflection\PhpParser\TraitReflection\TraitsSource\TraitsOtherTrait;
use ApiGen\ElementReflection\PhpParser\TraitReflection\TraitsSource\TraitsSomeOtherTrait;
use ApiGen\ElementReflection\PhpParser\TraitReflection\TraitsSource\TraitsSomeTrait;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\TraitReflectionInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class TraitsTest extends ParserAwareTestCase
{

	/**
	 * @var TraitReflection
	 */
	private $traitReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/TraitsSource');
		$this->traitReflection = $storage->getTrait(TraitsSomeTrait::class);
	}


	public function testNames()
	{
		$this->assertSame(TraitsSomeTrait::class, $this->traitReflection->getName());
	}


	public function testTraits()
	{
		$traits = $this->traitReflection->getTraits();
		$this->assertCount(2, $traits);

		$this->assertArrayHasKey(TraitsOtherTrait::class, $traits);
		$this->assertArrayHasKey(TraitsSomeOtherTrait::class, $traits);
	}


	public function testOwnTraits()
	{
		$traits = $this->traitReflection->getOwnTraits();
		$this->assertCount(1, $traits);

		$this->assertArrayHasKey(TraitsOtherTrait::class, $traits);
	}

}
