<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\ElementReflection\Magic;

use ApiGen\ElementReflection\Behaviors\AnnotationsInterface;
use ApiGen\ElementReflection\Magic\Factory\MagicParameterReflectionFactoryInterface;
use ApiGen\ElementReflection\Magic\Factory\MagicReflectionInterface;
use ApiGen\ElementReflection\Parser\AnnotationParser;
use ApiGen\ElementReflection\PhpParser\ClassReflection;


class MagicMethodReflection implements AnnotationsInterface, MagicReflectionInterface
{

	/**
	 * @var string
	 */
	const PATTERN_PARAMETER = '~^(?:([\\w\\\\]+(?:\\|[\\w\\\\]+)*)\\s+)?(&)?\\s*\\$(\\w+)(?:\\s*=\\s*(.*))?($)~s';

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var bool
	 */
	private $returnsReference;

	/**
	 * @var string
	 */
	private $declaringAnnotation;

	/**
	 * @var array
	 */
	private $arguments;

	/**
	 * @var ClassReflection
	 */
	private $declaringClass;

	/**
	 * @var MagicParameterReflectionFactoryInterface
	 */
	private $magicParameterReflectionFactory;

	/**
	 * @var MagicParameterReflection[]
	 */
	private $parameters;



	/**
	 * @param string $name
	 * @param string $shortDescription
	 * @param string $returnType
	 * @param bool $returnsReference
	 * @param array $arguments
	 * @param string $declaringAnnotation
	 * @param ClassReflection $declaringClass
	 * @param MagicParameterReflectionFactoryInterface $magicParameterReflectionFactory
	 */
	public function __construct(
		$name,
		$shortDescription,
		$returnType,
		$returnsReference,
		$arguments,
		$declaringAnnotation,
		ClassReflection $declaringClass,
		MagicParameterReflectionFactoryInterface $magicParameterReflectionFactory
	) {
		$this->name = $name;
		$this->annotations[AnnotationParser::SHORT_DESCRIPTION] = $shortDescription;
		$this->annotations['return'] = [0 => $returnType];
		$this->returnsReference = ($returnsReference === '&');
		$this->arguments = $arguments;
		$this->declaringAnnotation = $declaringAnnotation;
		$this->declaringClass = $declaringClass;
		$this->magicParameterReflectionFactory = $magicParameterReflectionFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return bool
	 */
	public function returnsReference()
	{
		return $this->returnsReference;
	}


	/**
	 * @return ClassReflection
	 */
	public function getDeclaringClass()
	{
		return $this->declaringClass;
	}


	/**
	 * @return MagicParameterReflection[]
	 */
	public function getParameters()
	{
		if ($this->parameters === NULL) {
			$parameters = [];
			foreach (array_filter(preg_split('~\\s*,\\s*~', $this->arguments)) as $position => $arg) {
				if ( ! preg_match(self::PATTERN_PARAMETER, $arg, $matches)) {
					// Wrong annotation format
					continue;
				}
				list(, $typeHint, $passedByReference, $name, $defaultValue) = $matches;

				$parameters[$name] = $this->magicParameterReflectionFactory->create(
					$name, $typeHint, $defaultValue, $passedByReference, $this
				);
				$this->annotations['param'][] = ltrim(sprintf('%s $%s', $typeHint, $name));
			}
			$this->parameters = $parameters;
		}
		return $this->parameters;
	}


	/**
	 * @return int
	 */
	public function getStartLine()
	{
		$doc = $this->declaringClass->getDocComment();
		$tmp = $this->declaringAnnotation;
		if ($delimiter = strpos($this->declaringAnnotation, "\n")) {
			$tmp = substr($this->declaringAnnotation, 0, $delimiter);
		}
		return $this->declaringClass->getStartLine() + substr_count(substr($doc, 0, strpos($doc, $tmp)), "\n");
	}


	/**
	 * @return int
	 */
	public function getEndLine()
	{
		return $this->getStartLine() + substr_count($this->declaringAnnotation, "\n");
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasAnnotation($name)
	{
		return isset($this->annotations[$name]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getAnnotation($name)
	{
		if ($this->hasAnnotation($name)) {
			return $this->annotations[$name];
		}
		return FALSE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getAnnotations()
	{
		return $this->annotations;
	}

}
