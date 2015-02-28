<?php

namespace ApiGen\ElementReflection\DI;

use ApiGen\ElementReflection\PhpParser\ClassReflection;
use ApiGen\ElementReflection\PhpParser\Factory\ClassReflectionFactoryInterface;
use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceDefinition;
use PHPUnit_Framework_TestCase;


class ElementReflectionExtensionTest extends PHPUnit_Framework_TestCase
{

	public function testLoadConfiguration()
	{
		$compiler = new Compiler(new ContainerBuilder);
		$extension = new ElementReflectionExtension;
		$extension->setCompiler($compiler, 'compiler');
		$extension->loadConfiguration();

		$builder = $compiler->getContainerBuilder();
		$builder->prepareClassList();

		/** @var ServiceDefinition $classFactory */
		$classFactoryName = $builder->getByType(ClassReflectionFactoryInterface::class);
		$classFactoryDefinition = $builder->getDefinition($classFactoryName);

		$this->assertSame(ClassReflection::class, $classFactoryDefinition->getClass());
	}

}
