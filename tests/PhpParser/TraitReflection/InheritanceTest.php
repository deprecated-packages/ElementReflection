<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\TraitReflection;

use ApiGen\ElementReflection\Parser;
use ApiGen\ElementReflection\PhpParser\TraitReflection;
use ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource\Trait1Trait;
use ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource\Trait2Trait;
use ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource\Trait3Trait;
use ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource\Trait4Trait;
use ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource\Traits;
use ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource\Traits2;
use ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource\Traits3;
use ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource\Traits4;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class InheritanceTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/InheritanceSource');
	}


	/**
	 * @dataProvider getTraitsDataProvider()
	 * @param string $traitName
	 * @param string[] $traitAliases
	 * @param int $traitCount
	 * @param int $ownTraitCount
	 */
	public function testTraits($traitName, $traitAliases, $traitCount, $ownTraitCount)
	{
//		$traitReflection = $this->storage->getTrait($traitName);
//		$this->assertInstanceOf(TraitReflection::class, $traitReflection);
//		$this->assertSame($traitAliases, $traitReflection->getTraitAliases());
//		$this->assertCount($traitCount, $traitReflection->getTraits());
//		$this->assertSame($ownTraitCount, count($traitReflection->getOwnTraits()));
	}


	/**
	 * @return array[]
	 */
	public function getTraitsDataProvider()
	{
		return [
//			[Trait1Trait::class, [], [], [], 0, 0],
			[Trait2Trait::class, [
				't2privatef' => '(null)::privatef'
			], [Trait1Trait::class], [Trait1Trait::class], 6, 3],
			[Trait3Trait::class, [], [], [], 0, 0],
			[Trait4Trait::class, [], [], [], 0, 0]
		];
	}


//	/**
//	 * @dataProvider getClassesDataProvider()
//	 * @param string $className
//	 * @param string[] $traitAliases
//	 * @param int $traitCount
//	 * @param int $ownTraitCount
//	 */
//	public function testClasses($className, $traitAliases, $traitCount, $ownTraitCount)
//	{
//		$classReflection = $this->storage->getClass($className);
//		$this->assertSame($traitAliases, $classReflection->getTraitAliases());
//		$this->assertSame($traitCount, count($classReflection->getTraits()));
//		$this->assertSame($ownTraitCount, count($classReflection->getOwnTraits()));
//	}
//
//
//	/**
//	 * @return array[]
//	 */
//	public function getClassesDataProvider()
//	{
//		return [
//			Traits::class => [
//				[
//					'privatef2' => '(null)::publicf', 'publicf3' => '(null)::protectedf', 'publicfOriginal' => '(null)::publicf'
//				], [Trait1Trait::class], [Trait1Trait::class], 6, 6
//			],
//			Traits2::class => [
//				[], [Trait2Trait::class], [Trait2Trait::class], 6, 3
//			],
//			Traits3::class => [
//				[], [Trait1Trait::class], [Trait1Trait::class], 6, 2
//			],
//			Traits4::class => [
//				[], [Trait3Trait::class, Trait4Trait::class], [Trait3Trait::class, Trait4Trait::class], 2, 1
//			]
//		];
//	}

}
