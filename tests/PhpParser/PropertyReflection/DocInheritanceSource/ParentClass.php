<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\PropertyReflection\DocInheritanceSource;


class ParentClass extends GrandParentClass
{

	/**
	 * {@inheritdoc} Protected1 short.
	 *
	 * Protected1 long. {@inheritdoc}
	 *
	 * @var mixed
	 */
	protected $param1;

	protected $param2;

	/**
	 * Protected3 {@inheritdoc} short.
	 *
	 * Protected3 long.
	 *
	 * @var mixed
	 */
	protected $param3;

	/**
	 * Protected4 short.
	 */
	protected $param4;

}
