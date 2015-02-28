# Element Reflection

[![Build Status](https://img.shields.io/travis/ApiGen/ElementReflection/master.svg?style=flat-square)](https://travis-ci.org/ApiGen/ElementReflection)
[![Quality Score](https://img.shields.io/scrutinizer/g/ApiGen/ElementReflection.svg?style=flat-square)](https://scrutinizer-ci.com/g/ApiGen/ElementReflection)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ApiGen/ElementReflection.svg?style=flat-square)](https://scrutinizer-ci.com/g/ApiGen/ElementReflection)
[![Downloads this Month](https://img.shields.io/packagist/dm/apigen/element-reflection.svg?style=flat-square)](https://packagist.org/packages/apigen/element-reflection)
[![Latest stable](https://img.shields.io/packagist/v/apigen/element-reflection.svg?style=flat-square)](https://packagist.org/packages/apigen/element-reflection)


This package scans PHP source and creates Reflection for every class, function, constant, property, method and parameter element found.


## Installation

Ad dependency via composer:

```sh
composer require apigen/element-reflection
```

Register extension in your `config.neon`:

```yaml
extensions:
	- ApiGen\ElementReflection\DI\ElementReflectionExtension
```


## Usage

```php
<?php

namespace ApiGen\ElementReflection;

use ApiGen\ElementReflection\Storage\MemoryStorage;

// parse source dir
$parser = new Parser(new MemoryStorage);
$storage = $parser->processDirectory(__DIR__ . '/vendor/doctrine');

$class = $storage->getClass('Doctrine\ORM\EntityManager'); // instance of ApiGen\ElementReflection\Reflection\ClassReflection
$class = $storage->getClass('Exception');    // instance of ApiGen\ElementReflection\Php\ClassReflection

$function = $storage->getFunction(...); // instance of ApiGen\ElementReflection\PhpParser\FunctionReflection
$constant = $storage->getConstant(...); // instance of ApiGen\ElementReflection\PhpParser\ConstantReflection
```


## Particular Reflections

- FunctionReflection
- ConstantReflection
- ClassReflection
- ClassConstantReflection
- PropertyReflection 
- MethodReflection
- ParameterReflection 


## Internal Elements - `ApiGen\ElementReflection\Php\*`

When you ask the Broker for an internal element e.g. `Zip` or `Phar`, it returns a `ApiGen\ElementReflection\Php\Reflection*` that encapsulates the internal reflection functionality and adds our features.
