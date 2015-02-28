<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Php\PropertyReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;
use ApiGen\ElementReflection\Tests\TestCase;
use ReflectionExtension;


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
		$this->assertInstanceOf(ReflectionExtension::class, $this->internalReflectionProperty->getExtension());
		$this->assertSame('Core', $this->internalReflectionProperty->getExtensionName());
	}

}
