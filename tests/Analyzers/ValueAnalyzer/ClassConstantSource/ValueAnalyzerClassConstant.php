<?php

namespace ApiGen\TokenReflection\Tests\Analyzers\ValueAnalyzer\ClassConstantSource;


class ValueAnalyzerClassConstant
{

	const SOME_CONSTANT = 5;

	public $someProperty = self::SOME_CONSTANT;

}
