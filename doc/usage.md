path: blob/master
source: lib/Report.php

# Usage
**LCOV Reports for PHP** provides a set of classes representing a [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) coverage report and its data.
The `Lcov\Report` class, the main one, provides the parsing and formatting features.

## Parse coverage data from a LCOV file
The `Report::fromCoverage()` static method parses a [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) coverage report provided as string, and creates a `Lcov\Report` instance giving detailed information about this coverage report:

```php
<?php
use Lcov\{LcovException, Report};

try {
  $coverage = @file_get_contents('lcov.info');
  $report = Report::fromCoverage($coverage);
  
  $count = count($report->getRecords());
  echo "The coverage report contains $count records:";
  print_r($report->jsonSerialize());
}

catch (LcovException $e) {
  echo 'An error occurred: ', $e->getMessage();
}
```

!!! info
    A `Lcov\LcovException` is thrown if any error occurred while parsing the coverage report.

The `Report->jsonSerialize()` instance method will return a [JSON](https://www.json.org) map like this:

```json
{
  "testName": "Example",
  "records": [
    {
      "sourceFile": "/home/cedx/lcov.php/fixture.php",
      "branches": {
        "found": 0,
        "hit": 0,
        "data": []
      },
      "functions": {
        "found": 1,
        "hit": 1,
        "data": [
          {"functionName": "main", "lineNumber": 4, "executionCount": 2}
        ]
      },
      "lines": {
        "found": 2,
        "hit": 2,
        "data": [
          {"lineNumber": 6, "executionCount": 2, "checksum": "PF4Rz2r7RTliO9u6bZ7h6g"},
          {"lineNumber": 9, "executionCount": 2, "checksum": "y7GE3Y4FyXCeXcrtqgSVzw"}
        ]
      }
    }
  ]
}
```

## Format coverage data to the LCOV format
Each provided class has a dedicated `__toString()` instance method returning the corresponding data formatted as [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) string.
All you have to do is to create the adequate structure using these different classes, and to export the final result:

```php
<?php
use Lcov\{FunctionCoverage, LineCoverage, LineData, Record, Report};

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

The `Report->__toString()` method will return a [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) report formatted like this:

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
