<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ParameterReflectionDefaultValuesByConstantSource;


const TOKEN_REFLECTION_PARAMETER_CONSTANT_VALUE = 'foo';


class ConstantValue
{

	const VALUE = 'bar';


	public function constantValue(
		$one = 'foo',
		$two = 'bar',
		$three = self::VALUE,
		$four = ConstantValue::VALUE,
		$five = TOKEN_REFLECTION_PARAMETER_CONSTANT_VALUE
	) {

	}

}
