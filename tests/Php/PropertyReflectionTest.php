<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Php\ExtensionReflection;
use ApiGen\ElementReflection\Php\PropertyReflection;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class PropertyReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var PropertyReflection
	 */
	private $internalReflectionProperty;


	protected function setUp()
	{
		parent::setUp();
		$this->internalReflectionProperty = $this->parser->getStorage()->getClass('Exception')->getProperty('message');
	}


	public function testName()
	{
		$this->assertSame('message', $this->internalReflectionProperty->getName());
		$this->assertSame('Exception::$message', $this->internalReflectionProperty->getPrettyName());
	}


	public function testValue()
	{
		$this->assertSame('', $this->internalReflectionProperty->getDefaultValue());
	}


	public function testGetExtension()
	{
		$extensionReflection = $this->internalReflectionProperty->getExtension();
		$this->assertInstanceOf(ExtensionReflection::class, $extensionReflection);
		$this->assertSame('Core', $extensionReflection->getName());
	}

}
