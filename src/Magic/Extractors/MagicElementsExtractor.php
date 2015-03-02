<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Magic\Extractors;

use ApiGen\ElementReflection\Magic\MagicMethodReflection;
use ApiGen\ElementReflection\PhpParser\ClassReflection;


class MagicElementsExtractor
{

	/**
	 * @var MagicMethodsExtractor
	 */
	private $magicMethodsExtractor;


	public function __construct(MagicMethodsExtractor $magicMethodsExtractor)
	{
		$this->magicMethodsExtractor = $magicMethodsExtractor;
	}


	/**
	 * @return MagicMethodReflection[]
	 */
	public function getMagicMethodsFromClass(ClassReflection $classReflection)
	{
		return $this->magicMethodsExtractor->extract($classReflection);
	}


//	/**
//	 * @return ReflectionMethodMagic[]
//	 */
//	public function getOwnMagicMethods()
//	{
//		if ($this->ownMagicMethods === NULL) {
//			$this->ownMagicMethods = [];
//
//			if ($this->reflectionClass->isVisibilityLevelPublic() && $this->reflectionClass->getDocComment()) {
//				$extractor = new AnnotationMethodExtractor($this->reflectionClass->getReflectionFactory());
//				$this->ownMagicMethods += $extractor->extractFromReflection($this->reflectionClass);
//			}
//		}
//		return $this->ownMagicMethods;
//	}
//

//	/**
//	 * @return array {[ declaringClassName => ReflectionMethodMagic[] ]}
//	 */
//	public function getInheritedMagicMethods()
//	{
//		$methods = [];
//		$allMethods = array_flip(array_map(function (ReflectionMethod $method) {
//			return $method->getName();
//		}, $this->getOwnMagicMethods()));
//
//		/** @var ReflectionClass[] $parentClassesAndInterfaces */
//		$parentClassesAndInterfaces = array_merge(
//			$this->reflectionClass->getParentClasses(), $this->reflectionClass->getInterfaces()
//		);
//		foreach ($parentClassesAndInterfaces as $class) {
//			$inheritedMethods = $this->getUsedElements($class->getOwnMagicMethods(), $allMethods);
//			$methods = $this->sortElements($inheritedMethods, $methods, $class);
//		}
//
//		return $methods;
//	}
//
//
//	/**
//	 * @return ReflectionMethodMagic[]|array
//	 */
//	public function getUsedMagicMethods()
//	{
//		$usedMethods = [];
//		foreach ($this->getMagicMethods() as $method) {
//			$declaringTraitName = $method->getDeclaringTraitName();
//			if ($declaringTraitName === NULL || $declaringTraitName === $this->reflectionClass->getName()) {
//				continue;
//			}
//			$usedMethods[$declaringTraitName][$method->getName()]['method'] = $method;
//		}
//		return $usedMethods;
//	}

}
