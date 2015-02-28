<?php

namespace ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource;


class Traits
{

	use Trait1Trait {
		publicf as private privatef2; protectedf as public publicf3; publicf as publicfOriginal;
	}

	private $classPrivate = 'classPrivate';

}
