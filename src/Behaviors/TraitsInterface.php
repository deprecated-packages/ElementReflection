<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Behaviors;

use ApiGen\ElementReflection\TraitReflectionInterface;


interface TraitsInterface
{

	/**
	 * Returns traits used by this class.
	 *
	 * @return TraitReflectionInterface[]
	 */
	function getTraits();


	/**
	 * Returns traits used by this class and not its parents.
	 *
	 * @return TraitReflectionInterface[]
	 */
	function getOwnTraits();


	/**
	 * Returns method aliases from traits.
	 *
	 * @return string[]
	 */
	function getTraitAliases();

}
