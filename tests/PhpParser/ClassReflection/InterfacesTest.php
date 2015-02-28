<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection;

use ApiGen\ElementReflection\InterfaceReflectionInterface;
use ApiGen\ElementReflection\Php\InterfaceReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\InterfacesSource\ClassInterfacesChildClass;
use ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\InterfacesSource\NoInterfaceClass;


class InterfacesTest extends ParserAwareTestCase
{

	/**
	 * @var StorageInterface
	 */
	private $storage;


	protected function setUp()
	{
		parent::setUp();
		$this->storage = $this->parser->parseDirectory(__DIR__ . '/InterfacesSource');
	}



	public function testInterfaces()
	{
		$classReflection = $this->storage->getClass(ClassInterfacesChildClass::class);

		$this->assertSame(
			['Traversable', 'Iterator', 'Countable', 'ArrayAccess', 'Serializable'],
			array_keys($classReflection->getInterfaces())
		);
		foreach ($classReflection->getInterfaces() as $interface) {
			$this->assertInstanceOf(InterfaceReflectionInterface::class, $interface);
		}
	}


	public function testOwnInterfaces()
	{
		$classReflection = $this->storage->getClass(ClassInterfacesChildClass::class);

		$this->assertSame(
			['Countable', 'ArrayAccess', 'Serializable'],
			array_keys($classReflection->getOwnInterfaces())
		);
		foreach ($classReflection->getOwnInterfaces() as $interface) {
			$this->assertInstanceOf(InterfaceReflection::class, $interface);
		}
	}


	public function testImplementsInterface()
	{
		$classReflection = $this->storage->getClass(ClassInterfacesChildClass::class);

		$this->assertTrue($classReflection->implementsInterface('Countable'));
	}


	public function testInternalInterface()
	{
		$token = $this->storage->getInterface('Iterator');
		$this->assertSame(['Traversable'], array_keys($token->getInterfaces()));
		$this->assertSame(['Traversable'], array_keys($token->getOwnInterfaces()));
	}


	public function testNoInterfaces()
	{
		$classReflection = $this->storage->getClass(NoInterfaceClass::class);

		$this->assertSame([], $classReflection->getInterfaces());
		$this->assertSame([], $classReflection->getOwnInterfaces());
		$this->assertFalse($classReflection->implementsInterface('Countable'));
	}

}
