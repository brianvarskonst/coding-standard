# Brianvarskonst CodeSniffer Ruleset

Provides a PHP CodeSniffer ruleset for the Brianvarskonst coding standard

[![Build Status](https://github.com/brianvarskonst/coding-standard/actions/workflows/ci.yml/badge.svg)](https://github.com/brianvarskonst/coding-standard/actions)
[![Code Coverage](https://codecov.io/gh/brianvarskonst/coding-standard/branch/master/graph/badge.svg)](https://codecov.io/gh/brianvarskonst/coding-standard/branch/master/)
[![Scrutinizer Quality Level](https://scrutinizer-ci.com/g/brianvarskonst/coding-standard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/brianvarskonst/coding-standard)
[![Codeclimate Maintainability](https://api.codeclimate.com/v1/badges/16114548a0315d993868/maintainability)](https://codeclimate.com/github/brianvarskonst/coding-standard/maintainability)

[![Latest Stable Version](https://poser.pugx.org/brianvarskonst/coding-standard/v/stable)](https://packagist.org/packages/brianvarskonst/coding-standard)
[![Total Downloads](https://poser.pugx.org/brianvarskonst/coding-standard/downloads)](https://packagist.org/packages/brianvarskonst/coding-standard)
[![Latest Unstable Version](https://poser.pugx.org/brianvarskonst/coding-standard/v/unstable)](https://packagist.org/packages/brianvarskonst/coding-standard)
[![composer.lock](https://poser.pugx.org/brianvarskonst/coding-standard/composerlock)](https://packagist.org/packages/brianvarskonst/coding-standard)

[![Minimum PHP Version](https://img.shields.io/packagist/php-v/brianvarskonst/coding-standard.svg?maxAge=3600)][https://packagist.org/packages/brianvarskonst/coding-standard]
[![Tested on PHP 7.4 to 8.3](https://img.shields.io/badge/tested%20on-PHP%205.4%20|%205.5%20|%205.6%20|%207.0%20|%207.1%20|%207.2%20|%207.3%20|%207.4%20|%208.0%20|%208.1%20|%208.2%20|%208.3-brightgreen.svg?maxAge=2419200)][https://github.com/brianvarskonst/coding-standard/actions/workflows/ci.yml]

[![License](https://poser.pugx.org/brianvarskonst/coding-standard/license)](https://packagist.org/packages/brianvarskonst/coding-standard)
![Awesome](https://img.shields.io/badge/awesome%3F-yes!-brightgreen.svg)

## Overview

The Brianvarskonst Coding Standard is an extension of the [Symfony Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html) and adds specific rules for ensuring code quality and consistency.

## Rules

### Brianvarskonst.Array.ArrayDoubleArrowAlignment
* `=>` operators must be aligned in associative arrays.
* Keys and `=>` operators must be on the same line in arrays.

### Brianvarskonst.Array.MultiLineArray
* Opening brackets must be followed by a newline in multi-line arrays.
* Closing brackets must be on their own line.
* Elements must be indented in multi-line arrays.

### Brianvarskonst.Formatting.AlphabeticalUseStatements
* `use` statements must be sorted lexicographically.
* Configurable sorting order via `order` property.

#### Configuration
The `order` property of the `Brianvarskonst.Formatting.AlphabeticalUseStatements` sniff defines
which function is used for ordering.

Possible values for order:
* `dictionary` (default): based on [strcmp](http://php.net/strcmp), the namespace separator
  precedes any other character
  ```php
  use Doctrine\ORM\Query;
  use Doctrine\ORM\Query\Expr;
  use Doctrine\ORM\QueryBuilder;
  ```
* `string`: binary safe string comparison using [strcmp](http://php.net/strcmp)
  ```php
  use Doctrine\ORM\Query;
  use Doctrine\ORM\QueryBuilder;
  use Doctrine\ORM\Query\Expr;

  use ExampleSub;
  use Examples;
  ```
* `string-locale`: locale based string comparison using [strcoll](http://php.net/strcoll)
* `string-case-insensitive`: binary safe case-insensitive string comparison [strcasecmp](http://php.net/strcasecmp)
   ```php
   use Examples;
   use ExampleSub;
   ```

To change the sorting order for your project, add this snippet to your custom `ruleset.xml`:

```xml
<rule ref="Brianvarskonst.Formatting.AlphabeticalUseStatements">
    <properties>
        <property name="order" value="string-locale"/>
    </properties>
</rule>
```

### Brianvarskonst.Formatting.UnnecessaryNamespaceUsageSniff
* The imported class name must be used, when it was imported with a `use` statement.

### Brianvarskonst.String.VariableInDoubleQuotes
* Interpolated variables in double-quoted strings must be surrounded by `{}`, e.g., `{$VAR}`. instead of `$VAR`.

### Brianvarskonst.WhiteSpace.ConstantSpacing
* `const` must be followed by a single space.

### Brianvarskonst.WhiteSpace.MultipleEmptyLines
Source: [mediawiki/mediawiki-codesniffer](https://github.com/wikimedia/mediawiki-tools-codesniffer)
* No more than one empty consecutive line is allowed.

### Brianvarskonst.Usage.ElementNameMinimalLength

* Functions, classes, interfaces, traits, and constants must use names with a minimum length (default 3 characters).
* Configurable via `minLength` and `allowedShortNames`.

```xml
<rule ref="Brianvarskonst.Usage.ElementNameMinimalLength">
    <properties>
        <property name="minLength" value="5"/>
        <property name="allowedShortNames" type="array" value="id,db,ok,x,y"/>
    </properties>
</rule>
```

alternatively, whitelist can be extended via `additionalAllowedNames` config, e.g.:

```xml
<rule ref="Brianvarskonst.Usage.ElementNameMinimalLength">
    <properties>
        <property name="additionalAllowedNames" type="array" value="i,j" />
    </properties>
</rule>
```

### Brianvarskonst.Complexity.NestingLevel

* Ensures a maximum nesting level within functions/methods.
* Default triggers a warning at level 3 and an error at level 5.
* Configurable via `warningLimit` and `errorLimit`.

For example:

```php
function foo(bool $level_one, array $level_two, bool $level_three)
{
    if ($level_one) {
        foreach ($level_two as $value) {
            if ($level_three) {
                return $value;
            }
        }
    }

    return '';
}
```

The example codes contains a nesting level of 3.

By default, the sniff triggers a _warning_ if nesting is equal or bigger than 3, and  triggers
an _error_ if nesting is equal or bigger than 5.

The warning and error limit can be customized via, respectively, `warningLimit` and `errorLimit`
properties:

```xml
<rule ref="Brianvarskonst.Complexity.NestingLevel">
    <properties>
        <property name="warningLimit" value="5" />
        <property name="errorLimit" value="10" />
    </properties>
</rule>
```

There's an exception. Normally a `try`/`catch`/`finally` blocks accounts for a nesting level,
but this sniff ignores the increase of level causes by a `try`/`catch`/`finally` that is found
immediately inside the level of function.

For example, the following code would be fine:

```php
function bar(array $data, string $foo): string
{
    // Indent level 1
    try {
        $encoded = json_encode($data, JSON_THROW_ON_ERROR);
        // Indent level 2
        if ($encoded) {
            // Indent level 3
            if ($append !== '') {
                return $encoded . $foo;
            }

            return $encoded;
        }

        return '';
    } catch (\Throwable $e) {
        return '';
    }
}
```

In fact, the two nested `if`s would account for an indent level of 2, plus the `try`/`catch`
block that would be 3, but because the `try`/`catch` is directly inside the function it is ignored,
so the max level considered by the sniff is 2, which is inside the limit.

This exception in the regard of `try`/`catch`/`finally` blocks can be disabled via the
`ignoreTopLevelTryBlock` property:

```xml
<rule ref="Brianvarskonst.Complexity.NestingLevel">
    <properties>
        <property name="errorLimit" value="10" />
        <property name="ignoreTopLevelTryBlock" value="false" />
    </properties>
</rule>
```

### Brianvarskonst.Classes.PropertyPerClassLimit

* Ensures a maximum number of properties per class (default 10).
* Configurable via `maxCount`.

```xml
<rule ref="Brianvarskonst.Classes.PropertyPerClassLimit">
    <properties>
        <property name="maxCount" value="120" />
    </properties>
</rule>
```

### Brianvarskonst.Namespace.Psr4

* Enforces PSR-4 autoload standards.
* Configurable to match `composer.json` autoload settings.

Our style enforce the use of PSR-4 for autoload.
This sniff make use of some configuration to check that files that contain classes are saved using
the structure expected by PSR-4.
If there is no configuration provided the sniff only checks that class name and match file name, which is
not a warranty of PSR-4.

The needed configuration is specular to the PSR-4 configuration in `composer.json` like:

```json
{
  "autoload": {
    "psr-4": {
      "Brianvarskonst\\Foo\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Brianvarskonst\\Foo\\Tests\\": "tests/php/"
    }
  }
}
```

The rule configuration should be:

```xml
<rule ref="Brianvarskonst.Namespace.Psr4">
    <properties>
        <property name="psr4" type="array" value="Brianvarskonst\Foo=>src,Brianvarskonst\Foo\Tests=>tests/php" />
    </properties>
</rule>
```

Please note that when a PSR-4 configuration is given, *all* autoloadable entities (classes/interfaces/trait)
are checked to be compliant.
If there are entities in the sniffer target paths that are not PSR-4 compliant (e.g. loaded via classmap
or not autoloaded at all) those should be excluded via `exclude` property, e.g.:

```xml
<rule ref="Brianvarskonst.Namespace.Psr4">
    <properties>
        <property name="psr4" type="array" value="Brianvarskonst\SomeCode=>src" />
        <property name="exclude" type="array" value="Brianvarskonst\ExcludeThis,Brianvarskonst\AndThis" />
    </properties>
</rule>
```

Note that anything that *starts with* any of the values in the `exclude` array will be excluded.

E.g. by excluding `Brianvarskonst\ExcludeThis` things like `Brianvarskonst\ExcludeThis\Foo` and
`Brianvarskonst\ExcludeThis\Bar\Baz` will be excluded as well.

To make sure what's excluded is a namespace, and not a class with same name, just use `\` as last
character.


### Additional Rules

Further rules are imported from other standards, detailed in `Brianvarskonst/ruleset.xml`.

Most of the issues can be auto-fixed with `phpcbf`.

## Requirements

* [PHP](http://php.net)
* [Composer](https://getcomposer.org/)

## Installation

### Composer

Using [Composer](https://getcomposer.org/) is the preferred way.

1. Add the Brianvarskonst coding standard to `composer.json`

```bash
$ composer require --dev brianvarskonst/coding-standard
```

2. Use the coding standard:

```bash
$ ./vendor/bin/phpcs --standard=Brianvarskonst path/to/my/file.php
```

3. Optionally, set Brianvarskonst as the default coding standard:

```bash
$ ./vendor/bin/phpcs --config-set default_standard Brianvarskonst
```

### Source

1. Clone the repository:

```bash
$ git clone https://github.com/brianvarskonst/coding-standard.git
```

2. Install dependencies:

```
$ composer install
```

3. Verify coding standards:

```bash
$ ./vendor/bin/phpcs -i
```

4. Use the coding standard:

```bash
$ ./vendor/bin/phpcs --standard=Brianvarskonst path/to/my/file.php
```

5. Optionally, set Brianvarskonst as the default coding standard:

```
$ ./vendor/bin/phpcs --config-set default_standard Brianvarskonst
```

## Troubleshooting

If `phpcs` complains that `Brianvarskonst` coding standard is not installed, please check the installed coding standards with
`phpcs -i` and that `installed_paths` is set correctly with `phpcs --config-show`

## Dependencies

* [PHP CodeSniffer](https://github.com/phpcsstandards/PHP_CodeSniffer)
* [David Joos's Symfony Coding Standard](https://github.com/djoos/Symfony-coding-standard)
* [Composer installer for PHP_CodeSniffer coding standards](https://github.com/DealerDirect/phpcodesniffer-composer-installer)
* [Slevomat Coding Standard](https://github.com/slevomat/coding-standard)
* [PHPCSStandards / PHPCSUtils](https://github.com/PHPCSStandards/PHPCSUtils)

## Contributing

See [CONTRIBUTING.md](.github/CONTRIBUTING.md) for information.

## License

Copyright (c) 2024, Brianvarskonst under [MIT](LICENSE) License
