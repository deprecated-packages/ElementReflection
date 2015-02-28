<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection;

use ApiGen\ElementReflection\Behaviors\NamespacesInterface;
use ApiGen\ElementReflection\Behaviors\StartEndLineInterface;


interface ConstantReflectionInterface extends ReflectionInterface, StartEndLineInterface, NamespacesInterface
{

	/**
	 * Returns the unqualified name (UQN).
	 *
	 * @return string
	 */
	function getShortName();


	/**
	 * Returns the constant value.
	 *
	 * @return mixed
	 */
	function getValue();

}
