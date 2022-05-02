# Changelog

## Version [9.0.0](https://github.com/cedx/lcov.php/branches/compare/v8.3.0..9.0.0)
- Breaking change: using PHP 8 features, like enumerations, first-class callables and named arguments.
- Breaking change: raised the required [PHP](https://www.php.net) version.
- Breaking change: removed the `LcovException` class and replaced it by the `UnexpectedValueException` one.
- Breaking change: renamed the `Record` class to `File`.
- Breaking change: renamed the `Report->records` property to `files`.
- Breaking change: renamed the `Report::fromCoverage()` method to `fromString()`.
- Breaking change: replaced the getter/setter methods of most classes by properties.

## Version [8.3.0](https://github.com/cedx/lcov.php/branches/compare/v8.3.0..v8.2.0)
- Deprecated this package in favor of [`cedx/lcov.hx`](https://github.com/cedx/lcov.hx).
- Replaced the build system based on [Robo](https://robo.li) by [PowerShell](https://docs.microsoft.com/en-us/powershell) scripts.

## Version [8.2.0](https://github.com/cedx/lcov.php/branches/compare/v8.2.0..v8.1.0)
- Updated the documentation.
- Updated the package dependencies.

## Version [8.1.0](https://github.com/cedx/lcov.php/branches/compare/v8.1.0..v8.0.0)
- Updated the package dependencies.

## Version [8.0.0](https://github.com/cedx/lcov.php/branches/compare/v8.0.0..v7.0.0)
- Breaking change: raised the required [PHP](https://www.php.net) version.
- Breaking change: using PHP 7.4 features, like arrow functions and typed properties.

## Version [7.0.0](https://github.com/cedx/lcov.php/branches/compare/v7.0.0..v6.2.0)
- Breaking change: using camelcase instead of all caps for constants.

## Version [6.2.0](https://github.com/cedx/lcov.php/branches/compare/v6.2.0..v6.1.0)
- Modified the package layout.
- Updated the package dependencies.

## Version [6.1.0](https://github.com/cedx/lcov.php/branches/compare/v6.1.0..v6.0.0)
- Replaced the [Phing](https://www.phing.info) build system by [Robo](https://robo.li).
- Updated the package dependencies.

## Version [6.0.0](https://github.com/cedx/lcov.php/branches/compare/v6.0.0..v5.1.0)
- Breaking change: changed the signature of the `fromJson()` methods.

## Version [5.1.0](https://github.com/cedx/lcov.php/branches/compare/v5.1.0..v5.0.0)
- Dropped the dependency on [PHPUnit-Expect](https://github.com/cedx/phpunit-expect).

## Version [5.0.0](https://github.com/cedx/lcov.php/branches/compare/v5.0.0..v4.1.0)
- Breaking change: raised the required [PHP](https://www.php.net) version.
- Added the `offset` and `source` properties to the `LcovError` class.
- Added support for [phpDocumentor](https://www.phpdoc.org).
- Updated the package dependencies.

## Version [4.1.0](https://github.com/cedx/lcov.php/branches/compare/v4.1.0..v4.0.0)
- Added the `LcovException` class.

## Version [4.0.0](https://github.com/cedx/lcov.php/branches/compare/v4.0.0..v3.0.0)
- Breaking change: raised the required [PHP](https://www.php.net) version.
- Breaking change: using PHP 7.1 features, like nullable types and void functions.
- Added a user guide based on [MkDocs](http://www.mkdocs.org).
- Updated the package dependencies.

## Version [3.0.0](https://github.com/cedx/lcov.php/branches/compare/v3.0.0..v2.0.0)
- Breaking change: changed the signature of the data class constructors.
- Breaking change: most properties of data classes are now read-only.
- Breaking change: removed the `setData()` methods from the coverage classes.
- Breaking change: removed the `setRecords()` method from the `Report` class.

## Version [2.0.0](https://github.com/cedx/lcov.php/branches/compare/v2.0.0..v1.1.0)
- Breaking change: renamed the `lcov` namespace to `Lcov`.
- Breaking change: renamed the `fromJSON()` static methods to `fromJson`.
- Breaking change: renamed the `Report::parse()` static method to `fromCoverage`.
- Changed licensing for the [MIT License](https://opensource.org/licenses/MIT).
- Changed the naming convention: acronyms and abbreviations are capitalized like regular words, except for two-letter acronyms.
- Updated the package dependencies.

## Version [1.1.0](https://github.com/cedx/lcov.php/branches/compare/v1.1.0..v1.0.0)
- Enabled the strict typing.
- Replaced [phpDocumentor](https://www.phpdoc.org) documentation generator by [ApiGen](https://github.com/ApiGen/ApiGen).
- Updated the package dependencies.

## Version [1.0.0](https://github.com/cedx/lcov.php/branches/compare/v1.0.0..v0.4.1)
- Ported the unit test assertions from [TDD](https://en.wikipedia.org/wiki/Test-driven_development) to [BDD](https://en.wikipedia.org/wiki/Behavior-driven_development).
- Removed the dependency on the `cedx/enum` module.
- Updated the package dependencies.

## Version [0.4.1](https://github.com/cedx/lcov.php/branches/compare/v0.4.1..v0.4.0)
- Fixed a bug in `Report::parse()` method.

## Version [0.4.0](https://github.com/cedx/lcov.php/branches/compare/v0.4.0..v0.3.0)
- Updated the package dependencies.

## Version [0.3.0](https://github.com/cedx/lcov.php/branches/compare/v0.3.0..v0.2.0)
- All classes now implement the `JsonSerializable` interface.
- Updated the package dependencies.

## Version [0.2.0](https://github.com/cedx/lcov.php/branches/compare/v0.2.0..v0.1.0)
- Breaking change: changed the signature of most constructors.
- Empty test names are not included in the report output.
- Updated the package dependencies.

## Version 0.1.0
- Initial release.
