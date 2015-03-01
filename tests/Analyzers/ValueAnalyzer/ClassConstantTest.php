<?php

namespace ApiGen\ElementReflection\Analyzers\ValueAnalyzer;

use ApiGen\ElementReflection\Analyzers\ValueAnalyzer;
use ApiGen\ElementReflection\PhpParser\PropertyReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\TokenReflection\Tests\Analyzers\ValueAnalyzer\ClassConstantSource\ValueAnalyzerClassConstant;
use ApiGen\TokenReflection\Tests\Analyzers\ValueAnalyzer\ClassConstantSource\ValueAnalyzerClassConstantChildClass;
use ApiGen\TokenReflection\Tests\Analyzers\ValueAnalyzer\ClassConstantSource\ValueAnalyzerClassConstantFromAnotherAliasedClass;
use ApiGen\TokenReflection\Tests\Analyzers\ValueAnalyzer\ClassConstantSource\ValueAnalyzerClassConstantFromAnotherClass;
use Mockery;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\PropertyProperty;


class ClassConstantTest extends ParserAwareTestCase
{

	/**
	 * @var ValueAnalyzer
	 */
	private $valueAnalyzer;

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->valueAnalyzer = $this->container->getByType(ValueAnalyzer::class);
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/ClassConstantSource');
	}


	public function testClassConstantFetch()
	{
		$classReflection = $this->storage->getClass(ValueAnalyzerClassConstant::class);
		$propertyReflection = $classReflection->getProperty('someProperty');

		$propertyNode = $this->getPropertyReflectionNode($propertyReflection);
		$value = $this->valueAnalyzer->analyzeFromNodeAndClass($propertyNode, $propertyReflection->getDeclaringClass());
		$this->assertSame(5, $value);
	}


	public function testClassConstantFetchFromAnotherClass()
	{
		$classReflection = $this->storage->getClass(ValueAnalyzerClassConstantFromAnotherClass::class);
		$propertyReflection = $classReflection->getProperty('someProperty');

		$propertyNode = $this->getPropertyReflectionNode($propertyReflection);
		$value = $this->valueAnalyzer->analyzeFromNodeAndClass($propertyNode, $propertyReflection->getDeclaringClass());
		$this->assertSame(5, $value);
	}


	public function testClassConstantFetchFromAnotherAliasedClass()
	{
		$classReflection = $this->storage->getClass(ValueAnalyzerClassConstantFromAnotherAliasedClass::class);
		$propertyReflection = $classReflection->getProperty('someProperty');

		$propertyNode = $this->getPropertyReflectionNode($propertyReflection);
		$value = $this->valueAnalyzer->analyzeFromNodeAndClass($propertyNode, $propertyReflection->getDeclaringClass());
//		$this->assertSame(5, $value);
	}


	public function testClassConstantFetchFromParentClass()
	{
		$classReflection = $this->storage->getClass(ValueAnalyzerClassConstantChildClass::class);

		$propertyReflection = $classReflection->getProperty('someOtherProperty');

		$propertyNode = $this->getPropertyReflectionNode($propertyReflection);
		$value = $this->valueAnalyzer->analyzeFromNodeAndClass($propertyNode, $propertyReflection->getDeclaringClass());
		$this->assertSame(5, $value);
	}


	/**
	 * @return PropertyProperty
	 */
	private function getPropertyReflectionNode(PropertyReflection $propertyReflection)
	{
		$propertyReflectionReflection = new \ReflectionClass($propertyReflection);
		$nodePropertyReflectionReflection = $propertyReflectionReflection->getProperty('node');
		$nodePropertyReflectionReflection->setAccessible(TRUE);
		return $nodePropertyReflectionReflection->getValue($propertyReflection);
	}

}
