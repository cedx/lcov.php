<?php declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf, isType};
use PHPUnit\Framework\{Assert, TestCase};

/** @testdox Lcov\Report */
class ReportTest extends TestCase {

  /** @testdox ::fromCoverage() */
  function testFromCoverage(): void {
    // It should have a test name.
    $report = Report::fromCoverage((string) @file_get_contents('test/fixtures/lcov.info'));
    assertThat($report->getTestName(), equalTo('Example'));

    // It should contain three records.
    $records = $report->getRecords();
    assertThat($records, countOf(3));

    /** @var Record $firstRecord */
    $firstRecord = $records[0];
    assertThat($firstRecord, isInstanceOf(Record::class));
    assertThat($firstRecord->getSourceFile(), equalTo('/home/cedx/lcov.php/fixture.php'));

    /** @var Record $secondRecord */
    $secondRecord = $records[1];
    assertThat($secondRecord->getSourceFile(), equalTo('/home/cedx/lcov.php/func1.php'));

    /** @var Record $thirdRecord */
    $thirdRecord = $records[2];
    assertThat($thirdRecord->getSourceFile(), equalTo('/home/cedx/lcov.php/func2.php'));

    // It should have detailed branch coverage.
    /** @var Record $record */
    $record = $records[1];

    /** @var BranchCoverage $branches */
    $branches = $record->getBranches();
    assertThat($branches->getFound(), equalTo(4));
    assertThat($branches->getHit(), equalTo(4));

    $data = $branches->getData();
    assertThat($data, countOf(4));

    /** @var BranchData $branch */
    $branch = $data[0];
    assertThat($branch, isInstanceOf(BranchData::class));
    assertThat($branch->getLineNumber(), equalTo(8));

    // It should have detailed function coverage.
    /** @var Record $record */
    $record = $records[1];

    /** @var FunctionCoverage $functions */
    $functions = $record->getFunctions();
    assertThat($functions->getFound(), equalTo(1));
    assertThat($functions->getHit(), equalTo(1));

    $data = $functions->getData();
    assertThat($data, countOf(1));

    /** @var FunctionData $function */
    $function = $data[0];
    assertThat($function, isInstanceOf(FunctionData::class));
    assertThat($function->getFunctionName(), equalTo('func1'));

    // It should have detailed line coverage.
    /** @var Record $record */
    $record = $records[1];

    /** @var LineCoverage $lines */
    $lines = $record->getLines();
    assertThat($lines->getFound(), equalTo(9));
    assertThat($lines->getHit(), equalTo(9));

    $data = $lines->getData();
    assertThat($data, countOf(9));

    /** @var LineData $line */
    $line = $data[0];
    assertThat($line, isInstanceOf(LineData::class));
    assertThat($line->getChecksum(), equalTo('5kX7OTfHFcjnS98fjeVqNA'));

    // It should throw an exception if the report is invalid or empty.
    $this->expectException(LcovException::class);
    Report::fromCoverage('TN:Example');
  }

  /** @testdox ::fromJson() */
  function testFromJson(): void {
    // It should return an instance with default values for an empty map.
    $report = Report::fromJson(new \stdClass);
    assertThat($report->getRecords(), isEmpty());
    assertThat($report->getTestName(), isEmpty());

    // It should return an initialized instance for a non-empty map.
    $report = Report::fromJson((object) ['records' => [new \stdClass], 'testName' => 'LcovTest']);
    assertThat($report->getTestName(), equalTo('LcovTest'));

    $records = $report->getRecords();
    assertThat($records, countOf(1));
    assertThat($records[0], isInstanceOf(Record::class));
    assertThat($report->getTestName(), equalTo('LcovTest'));
  }

  /** @testdox ->jsonSerialize() */
  function testJsonSerialize(): void {
    // It should return a map with default values for a newly created instance.
    $map = (new Report)->jsonSerialize();
    assertThat(get_object_vars($map), countOf(2));
    assertThat($map->records, isEmpty());
    assertThat($map->testName, isEmpty());

    // It should return a non-empty map for an initialized instance.
    $map = (new Report('LcovTest', [new Record('')]))->jsonSerialize();
    assertThat(get_object_vars($map), countOf(2));
    assertThat($map->records, countOf(1));
    assertThat($map->records[0], isType('object'));
    assertThat($map->testName, equalTo('LcovTest'));
  }

  /** @testdox ->__toString() */
  function testToString(): void {
    // It should return a format like "TN:<testName>".
    assertThat((string) new Report, isEmpty());

    $record = new Record('');
    assertThat((string) new Report('LcovTest', [$record]), equalTo(str_replace('{{eol}}', PHP_EOL, "TN:LcovTest{{eol}}$record")));
  }
}
