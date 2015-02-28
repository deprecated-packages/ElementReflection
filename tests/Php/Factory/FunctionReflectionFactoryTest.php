<?php

namespace ApiGen\ElementReflection\Tests\Php\Factory;

use ApiGen\ElementReflection\Php\Factory\FunctionReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\FunctionReflection;
use ApiGen\ElementReflection\Tests\ContainerFactory;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;


class FunctionReflectionFactoryTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var FunctionReflectionFactoryInterface
	 */
	private $functionReflectionFactory;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	protected function setUp()
	{
		$this->functionReflectionFactory = $this->container->getByType(FunctionReflectionFactoryInterface::class);
	}


	public function testCreate()
	{
		$functionReflection = $this->functionReflectionFactory->create('count');
		$this->assertInstanceOf(FunctionReflection::class, $functionReflection);
	}

}
