<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Magic\Extractors;


use ApiGen\ElementReflection\Magic\Factory\MagicMethodReflectionFactoryInterface;
use ApiGen\ElementReflection\Magic\MagicMethodReflection;
use ApiGen\ElementReflection\Magic\MagicParameterReflection;
use ApiGen\ElementReflection\PhpParser\ClassReflection;


class MagicMethodsExtractor
{

	/**
	 * @var string
	 */
	const METHOD_ANNOTATION = 'method';

	/**
	 * @var string
	 */
	const PATTERN_METHOD = '~^(?:([\\w\\\\]+(?:\\|[\\w\\\\]+)*)\\s+)?(&)?\\s*(\\w+)\\s*\\(\\s*(.*)\\s*\\)\\s*(.*|$)~s';


//	/**
//	 * @return MagicMethodReflection[]
//	 */
//	public function extractFromClass(ClassReflection $classReflection)
//	{
//		$methods = [];
//
//		dump($methods);
//		die;
//
//		if ($parentClass = $reflectionClass->getParentClass()) {
//			$methods += $this->extractFromParentClass($parentClass, $reflectionClass->isDocumented());
//		}
//		if ($traits = $reflectionClass->getTraits()) {
//			$methods += $this->extractFromTraits($traits, $reflectionClass->isDocumented());
//		}
//		return $methods;
//	}

	/**
	 * @var MagicMethodReflectionFactoryInterface
	 */
	private $magicMethodReflectionFactory;


	public function __construct(MagicMethodReflectionFactoryInterface $magicMethodReflectionFactory)
	{
		$this->magicMethodReflectionFactory = $magicMethodReflectionFactory;
	}


	/**
	 * @return MagicMethodReflection[]
	 */
	public function extract(ClassReflection $classReflection)
	{
		$annotations = $classReflection->getAnnotations();
		$methods = [];
		if (isset($annotations[self::METHOD_ANNOTATION])) {
			foreach ($annotations[self::METHOD_ANNOTATION] as $methodAnnotation) {
				$magicMethodReflection = $this->processMagicMethodAnnotation($methodAnnotation, $classReflection);
				$methods[$magicMethodReflection->getName()] = $magicMethodReflection;
			}
		}
		return $methods;
	}


	/**
	 * @param string $annotation
	 * @return MagicMethodReflection[]
	 */
	private function processMagicMethodAnnotation($annotation, ClassReflection $classReflection)
	{
		if ( ! preg_match(self::PATTERN_METHOD, $annotation, $matches)) {
			return [];
		}
		list(, $returnType, $returnsReference, $name, $arguments, $shortDescription) = $matches;

//		$startLine = $this->getStartLine($annotation, $classReflection);
//		$endLine = $startLine + substr_count($annotation, "\n");


		return $this->magicMethodReflectionFactory->create(
			$name,
			$shortDescription,
			$returnType,
			$returnsReference,
			$arguments,
			$annotation,
			$classReflection
		);

//		return $this->magicMethodReflectionFactory->create(
//			'name' => $name,
//			'shortDescription' => str_replace("\n", ' ', $shortDescription),
//			'startLine' => $startLine,
//			'endLine' => $endLine,
//			'returnsReference' => ($returnsReference === '&'),
//			'declaringClass' => $classReflection,
//			'annotations' => ['return' => [0 => $returnTypeHint]],
//			'argumentss' => $arguments
//		]);
	}




//	/**
//	 * @param ReflectionClass $parent
//	 * @param bool $isDocumented
//	 * @return ReflectionMethodMagic[]
//	 */
//	private function extractFromParentClass(ReflectionClass $parent, $isDocumented)
//	{
//		$methods = [];
//		while ($parent) {
//			$methods = $this->extractOwnFromClass($parent, $isDocumented, $methods);
//			$parent = $parent->getParentClass();
//		}
//		return $methods;
//	}


//	/**
//	 * @param array $traits
//	 * @param bool $isDocumented
//	 * @return ReflectionMethodMagic[]
//	 */
//	private function extractFromTraits($traits, $isDocumented)
//	{
//		$methods = [];
//		foreach ($traits as $trait) {
//			if ( ! $trait instanceof ReflectionClass) {
//				continue;
//			}
//			$methods = $this->extractOwnFromClass($trait, $isDocumented, $methods);
//		}
//		return $methods;
//	}
//	/**
//	 * @param ReflectionClass $reflectionClass
//	 * @param bool $isDocumented
//	 * @param array $methods
//	 * @return ReflectionMethodMagic[]
//	 */
//	private function extractOwnFromClass(ReflectionClass $reflectionClass, $isDocumented, array $methods)
//	{
//		foreach ($reflectionClass->getOwnMagicMethods() as $method) {
//			if ($this->canBeExtracted($isDocumented, $methods, $method)) {
//				$methods[$method->getName()] = $method;
//			}
//		}
//		return $methods;
//	}
//	/**
//	 * @param bool $isDocumented
//	 * @param array $methods
//	 * @param ReflectionMethodMagic $method
//	 * @return bool
//	 */
//	private function canBeExtracted($isDocumented, array $methods, ReflectionMethodMagic $method)
//	{
//		if (isset($methods[$method->getName()])) {
//			return FALSE;
//		}
//		if ($isDocumented && ! $method->isDocumented()) {
//			return FALSE;
//		}
//		return TRUE;
//	}

}
