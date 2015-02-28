<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Parser;

use ApiGen\ElementReflection\Behaviors\AnnotationsInterface;
use ApiGen\ElementReflection\ClassLikeReflectionInterface;
use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\MethodReflectionInterface;
use ApiGen\ElementReflection\PropertyReflectionInterface;
use ApiGen\ElementReflection\ReflectionInterface;


class AnnotationParser
{

	/**
	 * @var string
	 */
	const INHERITDOC = '{@inheritdoc}';

	/**
	 * Main description annotation identifier.
	 *
	 * @var string
	 */
	const SHORT_DESCRIPTION = 'short_description';

	/**
	 * Sub description annotation identifier.
	 *
	 * @var string
	 */
	const LONG_DESCRIPTION = 'long_description';

	/**
	 * @var ReflectionInterface|MethodReflectionInterface|PropertyReflectionInterface
	 */
	private $reflection;


	/**
	 * @return array[]
	 */
	public function inheritForMethod(MethodReflectionInterface $methodReflection, array $annotations)
	{
		$this->reflection = $methodReflection;
		return $this->inheritAnnotations($annotations);
	}


	/**
	 * @return array[]
	 */
	public function inheritForClass(ClassLikeReflectionInterface $classReflection, array $annotations)
	{
		$this->reflection = $classReflection;
		return $this->inheritAnnotations($annotations);
	}


	/**
	 * Inherits annotations from parent classes/methods/properties if needed.
	 *
	 * @param array $annotations
	 * @return array
	 */
	private function inheritAnnotations($annotations)
	{
		$parentDefinitions = $this->getParentDefinitions();

		if ($annotations === []) {
			// Inherit the entire docblock
			foreach ($parentDefinitions as $key => $parent) {
				if (count($parent->getAnnotations())) {
					$annotations = $parent->getAnnotations();
					break;
				}
			}

		} else {
			$annotations = $this->inheritLongDescription($parentDefinitions, $annotations);
			$annotations = $this->inheritShortDescription($parentDefinitions, $annotations);
		}

		$annotations = $this->inheritVar($parentDefinitions, $annotations);

		if ($this->reflection instanceof MethodReflectionInterface) {
			$annotations = $this->inheritParam($parentDefinitions, $annotations);

			// And check if we need and can inherit the return and throws value
			foreach (['return', 'throws'] as $paramName) {
				if ( ! isset($annotations[$paramName])) {
					foreach ($parentDefinitions as $parent) {
						if ($parent->hasAnnotation($paramName)) {
							$annotations[$paramName] = $parent->getAnnotation($paramName);
							break;
						}
					}
				}
			}
		}

		return $annotations;
	}


	/**
	 * @return array
	 */
	private function getParents()
	{
		if ($this->reflection instanceof ClassReflectionInterface) {
			$declaringClass = $this->reflection;

		} else {
			$declaringClass = $this->reflection->getDeclaringClass();
		}

		$parents = array_filter(array_merge([$declaringClass->getParentClass()], $declaringClass->getOwnInterfaces()), function ($class) {
			return $class instanceof ClassReflectionInterface;
		});

		return $parents;
	}


	/**
	 * @return array|AnnotationsInterface[]
	 */
	private function getParentDefinitions()
	{
		$parents = $this->getParents();

		// In case of properties and methods, look for a property/method of the same name and return
		// and array of such members.
		$parentDefinitions = [];
		if ($this->reflection instanceof PropertyReflectionInterface) {
			$name = $this->reflection->getName();
			/** @var ClassReflectionInterface $parent */
			foreach ($parents as $parent) {
				if ($parent->hasProperty($name)) {
					$parentDefinitions[] = $parent->getProperty($name);
				}
			}
			return $parentDefinitions;

		} elseif ($this->reflection instanceof MethodReflectionInterface) {
			$name = $this->reflection->getName();
			/** @var ClassReflectionInterface $parent */
			foreach ($parents as $parent) {
				if ($parent->hasMethod($name)) {
					$parentDefinitions[] = $parent->getMethod($name);
				}
			}
			return $parentDefinitions;
		}

		return $parents;
	}


	/**
	 * @param array|AnnotationsInterface[] $parentDefinitions
	 * @param array $annotations
	 * @return array
	 */
	private function inheritLongDescription(array $parentDefinitions, array $annotations)
	{
		if (isset($annotations[self::LONG_DESCRIPTION]) && FALSE !== stripos($annotations[self::LONG_DESCRIPTION], self::INHERITDOC)) {
			// Inherit long description
			foreach ($parentDefinitions as $parent) {
				if ($parent->hasAnnotation(self::LONG_DESCRIPTION)) {
					$annotations[self::LONG_DESCRIPTION] = str_ireplace(
						self::INHERITDOC,
						$parent->getAnnotation(self::LONG_DESCRIPTION),
						$annotations[self::LONG_DESCRIPTION]
					);
					break;
				}
			}
			$annotations[self::LONG_DESCRIPTION] = str_ireplace(self::INHERITDOC, '', $annotations[self::LONG_DESCRIPTION]);
		}

		return $annotations;
	}


	/**
	 * @param array|AnnotationsInterface[] $parentDefinitions
	 * @param array $annotations
	 * @return array
	 */
	private function inheritShortDescription(array $parentDefinitions, array $annotations)
	{
		if (isset($annotations[self::SHORT_DESCRIPTION]) && FALSE !== stripos($annotations[self::SHORT_DESCRIPTION], self::INHERITDOC)) {
			// Inherit short description
			foreach ($parentDefinitions as $parent) {
				if ($parent->hasAnnotation(self::SHORT_DESCRIPTION)) {
					$annotations[self::SHORT_DESCRIPTION] = str_ireplace(
						self::INHERITDOC,
						$parent->getAnnotation(self::SHORT_DESCRIPTION),
						$annotations[self::SHORT_DESCRIPTION]
					);
					break;
				}
			}
			$annotations[self::SHORT_DESCRIPTION] = str_ireplace(self::INHERITDOC, '', $annotations[self::SHORT_DESCRIPTION]);
		}
		return $annotations;
	}


	/**
	 * @param array|AnnotationsInterface[] $parentDefinitions
	 * @param array $annotations
	 * @return array
	 */
	private function inheritVar(array $parentDefinitions, array $annotations)
	{
		// In case of properties check if we need and can inherit the data type
		if ($this->reflection instanceof PropertyReflectionInterface && empty($annotations['var'])) {
			foreach ($parentDefinitions as $parent) {
				if ($parent->hasAnnotation('var')) {
					$annotations['var'] = $parent->getAnnotation('var');
					break;
				}
			}
		}

		return $annotations;
	}


	/**
	 * @param array|AnnotationsInterface[] $parentDefinitions
	 * @param array $annotations
	 * @return array
	 */
	private function inheritParam(array $parentDefinitions, array $annotations)
	{
		$parameterCount = count($this->reflection->getParameters());
		if ($parameterCount !== 0 &&
			(empty($annotations['param']) || count($annotations['param']) < $parameterCount)
		) {
			// In case of methods check if we need and can inherit parameter descriptions
			$params = isset($annotations['param']) ? $annotations['param'] : [];
			$complete = FALSE;
			foreach ($parentDefinitions as $parent) {
				if ($parent->hasAnnotation('param')) {
					$parentParams = array_slice($parent->getAnnotation('param'), count($params));
					while ( ! empty($parentParams) && !$complete) {
						array_push($params, array_shift($parentParams));
						if (count($params) === $parameterCount) {
							$complete = TRUE;
						}
					}
				}
				if ($complete) {
					break;
				}
			}
			if ( ! empty($params)) {
				$annotations['param'] = $params;
			}
		}
		return $annotations;
	}

}
