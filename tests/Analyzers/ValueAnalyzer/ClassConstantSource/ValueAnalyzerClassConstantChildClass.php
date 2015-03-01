<?php

namespace ApiGen\TokenReflection\Tests\Analyzers\ValueAnalyzer\ClassConstantSource;


class ValueAnalyzerClassConstantChildClass extends ValueAnalyzerClassConstant
{

	public $someOtherProperty = parent::SOME_CONSTANT;

}
