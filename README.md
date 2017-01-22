# LCOV Reports for PHP
![Release](https://img.shields.io/packagist/v/cedx/lcov.svg) ![License](https://img.shields.io/packagist/l/cedx/lcov.svg) ![Downloads](https://img.shields.io/packagist/dt/cedx/lcov.svg) ![Coverage](https://coveralls.io/repos/github/cedx/lcov.php/badge.svg) ![Build](https://travis-ci.org/cedx/lcov.php.svg)

Parse and format [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) coverage reports, in [PHP](https://secure.php.net).

## Requirements
The latest [PHP](https://secure.php.net) and [Composer](https://getcomposer.org) versions.
If you plan to play with the sources, you will also need the latest [Phing](https://www.phing.info) version.

## Installing via [Composer](https://getcomposer.org)
From a command prompt, run:

```shell
$ composer require cedx/lcov
```

## Usage
This package provides a set of classes representing a coverage report and its data.
The [`lcov\Report`](https://github.com/cedx/lcov.php/blob/master/lib/Report.php) class, the main one, provides the parsing and formatting features.

### Parse coverage data from a [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) file
The `Report::parse()` static method parses a coverage report provided as string, and returns a `Report` instance giving detailed information about this coverage report:

```php
use lcov\{Report};

try {
  $coverage = file_get_contents('lcov.info');
  $report = Report::parse($coverage);
  
  $count = count($report->getRecords());
  echo "The coverage report contains $count records:";
  print_r($report->jsonSerialize());
}

catch (\UnexpectedValueException $e) {
  echo 'The LCOV report has an invalid format.';
}
```

The `Report::jsonSerialize()` instance method will return a map like this:

```json
{
  "testName": "Example",
  "records": [
    {
      "sourceFile": "/home/cedx/lcov.php/fixture.php",
      "branches": {
        "data": [],
        "found": 0,
        "hit": 0
      },
      "functions": {
        "data": [
          {"executionCount": 2, "functionName": "main", "lineNumber": 4}
        ],
        "found": 1,
        "hit": 1
      },
      "lines": {
        "data": [
          {"checksum": "PF4Rz2r7RTliO9u6bZ7h6g", "executionCount": 2, "lineNumber": 6},
          {"checksum": "y7GE3Y4FyXCeXcrtqgSVzw", "executionCount": 2, "lineNumber": 9}
        ],
        "found": 2,
        "hit": 2
      }
    }
  ]
}

```

### Format coverage data to the [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) format
Each provided class has a dedicated `__toString()` instance method returning the corresponding data formatted as LCOV string.
All you have to do is to create the adequate structure using these different classes, and to export the final result:

```php
use lcov\{FunctionCoverage, LineCoverage, LineData, Record, Report};

$lineCoverage = new LineCoverage(2, 2, [
  new LineData(6, 2, 'PF4Rz2r7RTliO9u6bZ7h6g'),
  new LineData(7, 2, 'yGMB6FhEEAd8OyASe3Ni1w')
]);

$record = (new Record('/home/cedx/lcov.php/fixture.php'))
  ->setFunctions(new FunctionCoverage(1, 1))
  ->setLines($lineCoverage);

$report = new Report('Example', [$record]);
echo $report;
```

The `Report::__toString()` method will return a LCOV report formatted like this:

```
TN:Example
SF:/home/cedx/lcov.php/fixture.php
FNF:1
FNH:1
DA:6,2,PF4Rz2r7RTliO9u6bZ7h6g
DA:7,2,yGMB6FhEEAd8OyASe3Ni1w
LF:2
LH:2
end_of_record
```

## See also
- [Code coverage](https://coveralls.io/github/cedx/lcov.php)
- [Continuous integration](https://travis-ci.org/cedx/lcov.php)

## License
[LCOV Reports for PHP](https://github.com/cedx/lcov.php) is distributed under the Apache License, version 2.0.
