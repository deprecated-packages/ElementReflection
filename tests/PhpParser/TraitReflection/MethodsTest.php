<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\TraitReflection;

use ApiGen\ElementReflection\Parser;
use ApiGen\ElementReflection\PhpParser\TraitReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\TraitReflection\MethodsSource\MethodsSomeTrait;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class MethodsTest extends ParserAwareTestCase
{

	/**
	 * @var TraitReflection
	 */
	private $traitReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/MethodsSource');
		$this->traitReflection = $storage->getTrait(MethodsSomeTrait::class);
	}


	public function testMethods()
	{
		$methods = $this->traitReflection->getMethods();
		$this->assertCount(2, $methods);
	}


	public function testOwnMethods()
	{
		$methods = $this->traitReflection->getOwnMethods();
		$this->assertCount(1, $methods);
	}


	public function testGetTraits()
	{
		$this->traitReflection->getTraitAliases();
	}

}
