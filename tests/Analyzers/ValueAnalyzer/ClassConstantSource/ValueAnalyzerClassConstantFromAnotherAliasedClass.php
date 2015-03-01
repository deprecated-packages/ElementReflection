<?php

namespace ApiGen\TokenReflection\Tests\Analyzers\ValueAnalyzer\ClassConstantSource;

use ApiGen\TokenReflection\Tests\Analyzers\ValueAnalyzer\ClassConstantSource\ValueAnalyzerClassConstant as Alias;

class ValueAnalyzerClassConstantFromAnotherAliasedClass
{

	public $someProperty = Alias::SOME_CONSTANT;

}
