<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\MethodsSource;


class ClassMethodsChildClass extends ClassMethodsParentClass
{

	public function __construct($three)
	{
	}


	public function __destruct()
	{
	}


	public final function publicFinalFunction($four = 1)
	{
	}


	public static function publicStaticFunction($five = 1.1)
	{
	}


	private static function privateStaticFunction($six = 'string', $seven = NULL)
	{
	}


	public function publicFunction(array $eight = [])
	{
	}


	private function privateFunction(Foo $nine = NULL)
	{
	}

}
