<?php

namespace ApiGen\ElementReflection\Tests;

use ApiGen\ElementReflection\Parser\ParserInterface;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;


abstract class ParserAwareTestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var ParserInterface
	 */
	protected $parser;


	/**
	 * @param string|NULL $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct($name = NULL, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->container = (new ContainerFactory)->create();
	}


	protected function setUp()
	{
		$this->parser = $this->container->getByType(ParserInterface::class);
	}

}
