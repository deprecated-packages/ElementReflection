<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\TraitReflection;

use ApiGen\ElementReflection\Parser;
use ApiGen\ElementReflection\PhpParser\TraitReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\TraitReflection\TraitReflectionSource\SomeTrait;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class TraitReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var TraitReflection
	 */
	private $traitReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/TraitReflectionSource');
		$this->traitReflection = $storage->getTrait(SomeTrait::class);
	}


	public function testNames()
	{
		$this->assertSame(SomeTrait::class, $this->traitReflection->getName());
	}


//	public function testTraits2()
//	{
//		static $expected = [
//			'ElementReflection_Test_ClassTraitsTrait1' => [TRUE, [], [], [], 0, 0],
//			'ElementReflection_Test_ClassTraitsTrait2' => [TRUE, ['t2privatef' => '(null)::privatef'], ['ElementReflection_Test_ClassTraitsTrait1'], ['ElementReflection_Test_ClassTraitsTrait1'], 6, 3],
//			'ElementReflection_Test_ClassTraitsTrait3' => [TRUE, [], [], [], 0, 0],
//			'ElementReflection_Test_ClassTraitsTrait4' => [TRUE, [], [], [], 0, 0],
//			'ElementReflection_Test_ClassTraits' => [FALSE, ['privatef2' => '(null)::publicf', 'publicf3' => '(null)::protectedf', 'publicfOriginal' => '(null)::publicf'], ['ElementReflection_Test_ClassTraitsTrait1'], ['ElementReflection_Test_ClassTraitsTrait1'], 6, 6],
//			'ElementReflection_Test_ClassTraits2' => [FALSE, [], ['ElementReflection_Test_ClassTraitsTrait2'], ['ElementReflection_Test_ClassTraitsTrait2'], 6, 3],
//			'ElementReflection_Test_ClassTraits3' => [FALSE, [], ['ElementReflection_Test_ClassTraitsTrait1'], ['ElementReflection_Test_ClassTraitsTrait1'], 6, 2],
//			'ElementReflection_Test_ClassTraits4' => [FALSE, [], ['ElementReflection_Test_ClassTraitsTrait3', 'ElementReflection_Test_ClassTraitsTrait4'], ['ElementReflection_Test_ClassTraitsTrait3', 'ElementReflection_Test_ClassTraitsTrait4'], 2, 1]
//		];
//
//		$this->parser->parseFile($this->getFilePath('traits'));
//		foreach ($expected as $className => $definition) {
//			$reflection = $this->parser->getStorage()->getClass($className);
//			$this->assertSame($definition[0], $reflection->isTrait(), $className);
//			$this->assertSame($definition[1], $reflection->getTraitAliases(), $className);
//			$this->assertSame(count($definition[2]), count($reflection->getTraits()), $className);
//			$this->assertSame(count($definition[3]), count($reflection->getOwnTraits()), $className);
//		}
//	}

}
