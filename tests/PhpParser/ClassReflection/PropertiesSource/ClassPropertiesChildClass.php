<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\PropertiesSource;


class ClassPropertiesChildClass extends ClassPropertiesParentClass
{

	public static $publicStatic = TRUE;

	private static $privateStatic = 'something';

	public $public = FALSE;

	private $private = '';

}
