<?php

namespace ApiGen\ElementReflection\Tests\PhpParser\ClassReflection\ClassReflectionSource;


class SomeParentClass implements \Serializable
{

	/**
	 * Inherited doc
	 */
	public $someProperty;


	/**
	 * {@inheritdoc}
	 */
	public function serialize()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized)
	{
	}

}
