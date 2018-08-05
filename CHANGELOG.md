# Changelog

## Version [5.1.0](https://github.com/cedx/lcov.php/compare/v5.0.0...v5.1.0)
- Dropped the dependency on [PHPUnit-Expect](https://dev.belin.io/phpunit-expect).

## Version [5.0.0](https://github.com/cedx/lcov.php/compare/v4.1.0...v5.0.0)
- Breaking change: raised the required [PHP](https://secure.php.net) version.
- Added the `offset` and `source` properties to the `LcovError` class.
- Added support for [phpDocumentor](https://www.phpdoc.org).
- Updated the package dependencies.

## Version [4.1.0](https://github.com/cedx/lcov.php/compare/v4.0.0...v4.1.0)
- Added the `LcovException` class.

## Version [4.0.0](https://github.com/cedx/lcov.php/compare/v3.0.0...v4.0.0)
- Breaking change: raised the required [PHP](https://secure.php.net) version.
- Breaking change: using PHP 7.1 features, like nullable types and void functions.
- Added a user guide based on [MkDocs](http://www.mkdocs.org).
- Updated the package dependencies.

## Version [3.0.0](https://github.com/cedx/lcov.php/compare/v2.0.0...v3.0.0)
- Breaking change: changed the signature of the data class constructors.
- Breaking change: most properties of data classes are now read-only.
- Breaking change: removed the `setData()` methods from the coverage classes.
- Breaking change: removed the `setRecords()` method from the `Report` class.

## Version [2.0.0](https://github.com/cedx/lcov.php/compare/v1.1.0...v2.0.0)
- Breaking change: renamed the `lcov` namespace to `Lcov`.
- Breaking change: renamed the `fromJSON()` static methods to `fromJson`.
- Breaking change: renamed the `Report::parse()` static method to `fromCoverage`.
- Changed licensing for the [MIT License](https://opensource.org/licenses/MIT).
- Changed the naming convention: acronyms and abbreviations are capitalized like regular words, except for two-letter acronyms.
- Updated the package dependencies.

## Version [1.1.0](https://github.com/cedx/lcov.php/compare/v1.0.0...v1.1.0)
- Enabled the strict typing.
- Replaced [phpDocumentor](https://www.phpdoc.org) documentation generator by [ApiGen](https://github.com/ApiGen/ApiGen).
- Updated the package dependencies.

## Version [1.0.0](https://github.com/cedx/lcov.php/compare/v0.4.1...v1.0.0)
- Ported the unit test assertions from [TDD](https://en.wikipedia.org/wiki/Test-driven_development) to [BDD](https://en.wikipedia.org/wiki/Behavior-driven_development).
- Removed the dependency on the `cedx/enum` module.
- Updated the package dependencies.

## Version [0.4.1](https://github.com/cedx/lcov.php/compare/v0.4.0...v0.4.1)
- Fixed a bug in `Report::parse()` method.

## Version [0.4.0](https://github.com/cedx/lcov.php/compare/v0.3.0...v0.4.0)
- Updated the package dependencies.

## Version [0.3.0](https://github.com/cedx/lcov.php/compare/v0.2.0...v0.3.0)
- All classes now implement the `JsonSerializable` interface.
- Updated the package dependencies.

## Version [0.2.0](https://github.com/cedx/lcov.php/compare/v0.1.0...v0.2.0)
- Breaking change: changed the signature of most constructors.
- Empty test names are not included in the report output.
- Updated the package dependencies.

## Version 0.1.0
- Initial release.
