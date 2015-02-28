<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource;


/**
 * Some short description.
 *
 * Some long description.
 *
 *
 * @deprecated
 */
class SomeClass extends SomeParentClass implements \Countable
{

	const SOME_CONST = 'some value';


	public $someProperty;


	/**
	 * {@inheritdoc}
	 */
	public function count()
	{
		return 5;
	}

}
