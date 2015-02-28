<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Php\ParameterReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ReflectionExtension;


class ParameterReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ParameterReflection
	 */
	private $internalReflectionParameter;


	protected function setUp()
	{
		parent::setUp();
		$this->internalReflectionParameter = $this->parser->getStorage()
			->getClass('Exception')
			->getMethod('__construct')
			->getParameter('message');
	}


	public function testName()
	{
		$this->assertSame('message', $this->internalReflectionParameter->getName());
		$this->assertSame('Exception::__construct($message)', $this->internalReflectionParameter->getPrettyName());
	}


	public function testClasses()
	{
		$this->assertSame('Exception', $this->internalReflectionParameter->getDeclaringClass()->getName());
		$this->assertSame('__construct', $this->internalReflectionParameter->getDeclaringFunction()->getName());
	}


	public function testIsVariadic()
	{
		$this->assertFalse($this->internalReflectionParameter->isVariadic());
	}


	/**
	 * @expectedException \ReflectionException
	 */
	public function testGetDefaultValue()
	{
		$this->internalReflectionParameter->getDefaultValue();
	}


	public function testGetExtension()
	{
		$this->assertInstanceOf(ReflectionExtension::class, $this->internalReflectionParameter->getExtension());
		$this->assertSame('Core', $this->internalReflectionParameter->getExtensionName());
	}

}
