<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Php\ParameterReflection;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ReflectionExtension;


class ParameterReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ParameterReflection
	 */
	private $parameterReflection;


	protected function setUp()
	{
		parent::setUp();
		$this->parameterReflection = $this->parser->getStorage()
			->getClass('Exception')
			->getMethod('__construct')
			->getParameter('message');
	}


	public function testName()
	{
		$this->assertSame('message', $this->parameterReflection->getName());
		$this->assertSame('Exception::__construct($message)', $this->parameterReflection->getPrettyName());
	}


	public function testClasses()
	{
		$this->assertSame('Exception', $this->parameterReflection->getDeclaringClass()->getName());
		$this->assertSame('__construct', $this->parameterReflection->getDeclaringFunction()->getName());
	}


	public function testIsVariadic()
	{
		$this->assertFalse($this->parameterReflection->isVariadic());
	}


	public function testGetExtension()
	{
		$extensionReflection = $this->parameterReflection->getExtension();
		$this->assertInstanceOf(ReflectionExtension::class, $extensionReflection);
		$this->assertSame('Core', $extensionReflection->getName());
	}

}
