<?php

namespace ApiGen\ElementReflection\Tests;

use ApiGen;
use ApiGen\ElementReflection\Parser;


class ParserTest extends ParserAwareTestCase
{

	public function testFindFiles()
	{
		$storage = $this->parser->parseDirectory(__DIR__);
		$this->assertGreaterThanOrEqual(10, $storage->getClasses());
	}


	/**
	 * @expectedException ApiGen\ElementReflection\Exception\ParserException
	 */
	public function testFileProcessingError()
	{
		$file = __DIR__ . DIRECTORY_SEPARATOR . '~#nonexistent#~';
		$this->parser->parseFile($file);
	}


	/**
	 * @expectedException ApiGen\ElementReflection\Exception\ParserException
	 */
	public function testDirectoryProcessingError()
	{
		$file = __DIR__ . DIRECTORY_SEPARATOR . '~#nonexistent#~' . DIRECTORY_SEPARATOR . '~#nonexistent#~';
		$this->parser->parseDirectory($file);
	}

}
