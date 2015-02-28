<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DefaultSource;


class ParentClass
{

	const DEFAULT_VALUE = 'default';

	public $default = 'default';

	public $default2 = self::DEFAULT_VALUE;

	public $default3 = ParentClass::DEFAULT_VALUE;

}
