<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Php\ConstantReflection;
use ApiGen\ElementReflection\Storage\StorageInterface;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class ConstantReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var ConstantReflection
	 */
	private $internalReflectionConstant;


	protected function setUp()
	{
		parent::setUp();
		$this->internalReflectionConstant = $this->parser->getStorage()->getConstant('DIRECTORY_SEPARATOR');
	}


	public function testName()
	{
		$this->assertSame('DIRECTORY_SEPARATOR', $this->internalReflectionConstant->getName());
		$this->assertSame('DIRECTORY_SEPARATOR', $this->internalReflectionConstant->getShortName());
	}


	public function testBasicMethods()
	{
		$this->assertSame('DIRECTORY_SEPARATOR', $this->internalReflectionConstant->getPrettyName());
	}


	public function testValue()
	{
		$this->assertSame('/', $this->internalReflectionConstant->getValue());
	}

}
