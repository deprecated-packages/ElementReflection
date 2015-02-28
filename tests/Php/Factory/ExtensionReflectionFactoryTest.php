<?php

namespace ApiGen\ElementReflection\Tests\Php\Factory;

use ApiGen;
use ApiGen\ElementReflection\Php\Factory\ExtensionReflectionFactoryInterface;
use ApiGen\ElementReflection\Php\ExtensionReflection;
use ApiGen\ElementReflection\Tests\ContainerFactory;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;


class ExtensionReflectionFactoryTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var ExtensionReflectionFactoryInterface
	 */
	private $extensionReflectionFactory;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	protected function setUp()
	{
		$this->extensionReflectionFactory = $this->container->getByType(ExtensionReflectionFactoryInterface::class);
	}


	public function testCreate()
	{
		$extensionReflection = $this->extensionReflectionFactory->create('phar');
		$this->assertInstanceOf(ExtensionReflection::class, $extensionReflection);
	}

}
