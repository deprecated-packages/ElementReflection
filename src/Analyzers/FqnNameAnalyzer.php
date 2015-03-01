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
	 * @param Name $name
	 * @param string $namespace
	 * @return string
	 */
	public function compactFqnName(Name $name, $namespace)
	{
		if ($namespace) {
			return $namespace . AbstractReflection::NS_SEP . $name->toString();
		}
		return $name->toString();
	}


	/**
	 * @return string
	 */
	public function detectFqnName(Name $name, TraitReflection $parentClassLikeElement = NULL)
	{
		$name = $name->toString();

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
