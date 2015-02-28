<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\MethodReflection\DocInheritanceSource;


class ParentClass extends GrandParentClass
{

	/**
	 * {@inheritdoc} Protected1 short.
	 *
	 * Protected1 long. {@inheritdoc}
	 *
	 * @return string
	 */
	protected function method1()
	{
	}


	protected function method2()
	{
	}


	/**
	 * Protected3 {@inheritdoc} short.
	 *
	 * Protected3 long.
	 *
	 * @return bool
	 */
	protected function method3()
	{
	}


	protected function method4()
	{
	}

}
