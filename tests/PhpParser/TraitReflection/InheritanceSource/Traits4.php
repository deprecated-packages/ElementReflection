<?php

namespace ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource;


class Traits4
{

	use Trait3Trait;
	use Trait4Trait {
		Trait4Trait::privatef insteadof Trait3Trait;
	}

}
