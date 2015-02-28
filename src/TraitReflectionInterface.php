<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;

use ApiGen\ElementReflection\Behaviors\MethodsInterface;
use ApiGen\ElementReflection\Behaviors\PropertiesInterface;
use ApiGen\ElementReflection\Behaviors\TraitsInterface;


interface TraitReflectionInterface extends ClassLikeReflectionInterface, PropertiesInterface, MethodsInterface,
	TraitsInterface
{

}
