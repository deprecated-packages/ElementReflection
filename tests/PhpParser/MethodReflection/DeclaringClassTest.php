<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\MethodReflection;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\MethodReflection\DeclaringClassSource\ChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\MethodReflection\DeclaringClassSource\ParentClass;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class DeclaringClassTest extends ParserAwareTestCase
{

	/**
	 * @var ClassReflectionInterface
	 */
	private $childClassReflection;


	protected function setUp()
	{
		parent::setUp();
		/** @var StorageInterface $storage */
		$storage = $this->parser->parseDirectory(__DIR__ . '/DeclaringClassSource');
		$this->childClassReflection = $storage->getClass(ChildClass::class);
	}


	public function testDeclaringClass()
	{
		$parentMethod = $this->childClassReflection->getMethod('parent');
		$childMethod = $this->childClassReflection->getMethod('child');
		$parentOverlayMethod = $this->childClassReflection->getMethod('parentOverlay');

		$this->assertSame(ParentClass::class, $parentMethod->getDeclaringClass()->getName());
		$this->assertSame(ChildClass::class, $childMethod->getDeclaringClass()->getName());
		$this->assertSame(ChildClass::class, $parentOverlayMethod->getDeclaringClass()->getName());
	}

}
