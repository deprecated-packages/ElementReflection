<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionSource;

use ApiGen\ElementReflection\Tests\PhpParser\ClassConstantReflection\ClassConstantReflectionSource\SomeConstantInClass;


class SomeClass
{

	public function someMethod(SomeConstantInClass $someParameter = NULL)
	{
	}

}
