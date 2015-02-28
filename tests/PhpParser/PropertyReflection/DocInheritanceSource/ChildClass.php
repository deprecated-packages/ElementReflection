<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DocInheritanceSource;


class ChildClass extends ParentClass
{

	public $param1;

	public $param2;

	/**
	 * Public3 {@inheritdoc}
	 *
	 * @var mixed
	 */
	public $param3;

	public $param4;

}
