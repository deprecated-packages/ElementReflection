<?php

namespace ApiGen\ElementReflection\Analyzers;

use ApiGen\ElementReflection\PhpParser\TraitReflection;
use Mockery;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PHPUnit_Framework_TestCase;


class FqnNameAnalyzerTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var FqnNameAnalyzer
	 */
	private $fqnNameAnalyzer;


	public function __construct()
	{
		$this->fqnNameAnalyzer = new FqnNameAnalyzer;
	}


	public function testCompactFqnName()
	{
		$name = new Name(['name' => 'SomeName']);
		$this->assertSame('SomeNamespace\SomeName', $this->fqnNameAnalyzer->compactFqnName($name, 'SomeNamespace'));
		$this->assertSame('SomeName', $this->fqnNameAnalyzer->compactFqnName($name, ''));
	}


	public function testDetectFqnName()
	{
		$traitName = new Name(['name' => 'SomeName']);
		$this->assertSame('SomeName', $this->fqnNameAnalyzer->detectFqnName($traitName));
	}


	public function testDetectFqnNameWithNamespace()
	{
		$parentClassLikeElement = Mockery::mock(TraitReflection::class);
		$namespace = new Namespace_(new Name(['name' => 'SomeNamespace']));
		$parentClassLikeElement->shouldReceive('getParentNode')->andReturn($namespace);
		$traitName = new Name(['name' => 'SomeName']);
		$this->assertSame(
			'SomeNamespace\SomeName',
			$this->fqnNameAnalyzer->detectFqnName($traitName, $parentClassLikeElement)
		);
	}

}
