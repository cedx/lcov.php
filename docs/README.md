# LCOV Reports for PHP
Parse and format [LCOV](https://github.com/linux-test-project/lcov) coverage reports, in [PHP](https://www.php.net).

## Quick start
Install the latest version of **LCOV Reports for PHP** with [Composer](https://getcomposer.org) package manager:

``` shell
composer require cedx/lcov
```

For detailed instructions, see the [installation guide](installation.md).

## Usage
This library provides a set of classes representing a [LCOV](https://github.com/linux-test-project/lcov) coverage report and its data.
The `Report` class, the main one, provides the parsing and formatting features.

- [Parse coverage data from a LCOV file](usage/parsing.md)
- [Format coverage data to the LCOV format](usage/formatting.md)

## See also
- [API reference](api/)
- [Packagist package](https://packagist.org/packages/cedx/lcov)
- [Code coverage](https://app.codecov.io/gh/cedx/lcov.php)
