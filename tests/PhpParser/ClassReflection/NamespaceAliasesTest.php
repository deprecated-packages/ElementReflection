<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection;

use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\NamespaceAliasesSource\NamespaceAliasesClass;


class NamespaceAliasesTest extends ParserAwareTestCase
{

	/**
	 * @var ClassReflection
	 */
	private $classReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/NamespaceAliasesSource');
		$this->classReflection = $storage->getClass(NamespaceAliasesClass::class);
	}


	public function testNamespaceAliases()
	{
		$aliases = $this->classReflection->getNamespaceAliases();
		$this->assertCount(2, $aliases);

		$this->assertSame([
			'AliasThat' => 'That',
			'ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\NamespaceAliasesSource' =>
			'ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\NamespaceAliasesSource'
		], $aliases);
	}

}
