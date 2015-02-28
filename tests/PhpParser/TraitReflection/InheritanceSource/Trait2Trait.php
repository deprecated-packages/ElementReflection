<?php

namespace ApiGen\ElementReflection\PhpParser\TraitReflection\InheritanceSource;


trait Trait2Trait
{

	use Trait1Trait {
		privatef as t2privatef;
	}


	private function privatef()
	{
	}

}
