<?php

namespace ApiGen\ElementReflection\Tests\Php;

use ApiGen;
use ApiGen\ElementReflection\Php\ExtensionReflection;
use ApiGen\ElementReflection\Php\FunctionReflection;
use ApiGen\ElementReflection\Php\ParameterReflection;
use ApiGen\ElementReflection\Tests\ParserAwareTestCase;


class FunctionReflectionTest extends ParserAwareTestCase
{

	/**
	 * @var FunctionReflection
	 */
	private $internalReflectionFunction;


	protected function setUp()
	{
		parent::setUp();
		$this->internalReflectionFunction = $this->parser->getStorage()->getFunction('count');
	}


	public function testName()
	{
		$this->assertSame('count', $this->internalReflectionFunction->getName());
		$this->assertSame('count()', $this->internalReflectionFunction->getPrettyName());
	}


	public function testParameters()
	{
		$this->assertCount(2, $this->internalReflectionFunction->getParameters());
		$this->assertInstanceOf(
			ParameterReflection::class,
			$this->internalReflectionFunction->getParameter('var')
		);
	}


	public function testIsVariadic()
	{
		$this->assertFalse($this->internalReflectionFunction->isVariadic());
	}


	public function testGetExtension()
	{
		$this->assertInstanceOf(ExtensionReflection::class, $this->internalReflectionFunction->getExtension());
	}

}
