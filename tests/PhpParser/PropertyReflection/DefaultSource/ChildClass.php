<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DefaultSource;


class ChildClass extends ParentClass
{

	const DEFAULT_VALUE = 'not default';

	const PARENT_DEFAULT_VALUE = parent::DEFAULT_VALUE;

	public $default4 = self::DEFAULT_VALUE;

	public $default5 = ChildClass::DEFAULT_VALUE;

	public $default6 = parent::DEFAULT_VALUE;

	public $default7 = ParentClass::DEFAULT_VALUE;

	public $default8 = self::PARENT_DEFAULT_VALUE;

	public $default9 = [self::DEFAULT_VALUE, parent::DEFAULT_VALUE, self::PARENT_DEFAULT_VALUE];

}
