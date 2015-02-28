<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Inheritor;

use ApiGen\ElementReflection\ClassReflectionInterface;
use ApiGen\ElementReflection\MethodReflectionInterface;
use ApiGen\ElementReflection\Parser\AnnotationParser;


class AnnotationInheritor
{

	/**
	 * @var AnnotationParser
	 */
	private $annotationParser;


	public function __construct(AnnotationParser $annotationParser)
	{
		$this->annotationParser = $annotationParser;
	}


	/**
	 * @param MethodReflectionInterface $methodReflection
	 * @param array $annotations
	 * @return \array[]
	 */
	public function inheritForMethod(MethodReflectionInterface $methodReflection, array $annotations)
	{
		return $this->annotationParser->inheritForMethod($methodReflection, $annotations);
	}


	/**
	 * @param ClassReflectionInterface $classReflection
	 * @param array $annotations
	 * @return mixed
	 */
	public function inheritForClass(ClassReflectionInterface $classReflection, array $annotations)
	{
		return $this->annotationParser->inheritForClass($classReflection, $annotations);
	}

}
