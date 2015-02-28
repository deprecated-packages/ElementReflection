<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Analyzers;

use ApiGen\ElementReflection\PhpParser\AbstractReflection;
use ApiGen\ElementReflection\PhpParser\TraitReflection;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;


class FqnNameAnalyzer
{

	/**
	 * @return string
	 * @todo test
	 */
	public function detectFqnName(Name $trait, TraitReflection $parentClassLikeElement = NULL)
	{
		$name = $trait->toString();

		$namespaceName = '';
		if ($parentClassLikeElement) {
			if ($parentClassLikeElement->getParentNode()) {
				if ($parentClassLikeElement->getParentNode() instanceof Namespace_) {
					/** @var Namespace_ $namespace */
					$namespace = $parentClassLikeElement->getParentNode();
					$namespaceName = $namespace->name->toString();
				}
			}
		}

		if ($namespaceName) {
			return $namespaceName . AbstractReflection::NS_SEP . $name;
		}
		return $name;
	}


}
