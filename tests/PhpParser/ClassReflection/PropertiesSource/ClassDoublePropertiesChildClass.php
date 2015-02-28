<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\PropertiesSource;


class ClassDoublePropertiesChildClass extends ClassDoublePropertiesParentClass
{

	public $publicOne = TRUE, $publicTwo = FALSE;

	private $privateOne = 'something', $privateTwo = '';

}
