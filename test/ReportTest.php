<?php
declare(strict_types=1);
namespace Lcov;

use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `Lcov\Report` class.
 */
class ReportTest extends TestCase {

  /**
   * @test Report::fromCoverage
   */
  public function testFromCoverage(): void {
    $report = Report::fromCoverage(file_get_contents('test/fixtures/lcov.info'));
    $records = $report->getRecords();

    // It should have a test name', function() use ($report) {
    assertThat($report->getTestName(), equalTo('Example'));

    // It should contain three records', function() use ($records) {
    assertThat($records, countOf(3));
    assertThat($records[0], isInstanceOf(Record::class));
    assertThat($records[0]->getSourceFile(), equalTo('/home/cedx/lcov.php/fixture.php'));
    assertThat($records[1]->getSourceFile(), equalTo('/home/cedx/lcov.php/func1.php'));
    assertThat($records[2]->getSourceFile(), equalTo('/home/cedx/lcov.php/func2.php'));

    // It should have detailed branch coverage', function() use ($records) {
    /** @var BranchCoverage $branches */
    $branches = $records[1]->getBranches();
    assertThat($branches->getFound(), equalTo(4));
    assertThat($branches->getHit(), equalTo(4));

    $data = $branches->getData();
    assertThat($data, countOf(4));
    assertThat($data[0], isInstanceOf(BranchData::class));
    assertThat($data[0]->getLineNumber(), equalTo(8));

    // It should have detailed function coverage', function() use ($records) {
    /** @var FunctionCoverage $functions */
    $functions = $records[1]->getFunctions();
    assertThat($functions->getFound(), equalTo(1));
    assertThat($functions->getHit(), equalTo(1));

    $data = $functions->getData();
    assertThat($data, countOf(1));
    assertThat($data[0], isInstanceOf(FunctionData::class));
    assertThat($data[0]->getFunctionName(), equalTo('func1'));

    // It should have detailed line coverage', function() use ($records) {
    /** @var LineCoverage $lines */
    $lines = $records[1]->getLines();
    assertThat($lines->getFound(), equalTo(9));
    assertThat($lines->getHit(), equalTo(9));

    $data = $lines->getData();
    assertThat($data, countOf(9));
    assertThat($data[0], isInstanceOf(LineData::class));
    assertThat($data[0]->getChecksum(), equalTo('5kX7OTfHFcjnS98fjeVqNA'));

    // It should throw an error if the report is invalid or empty.
    $this->expectException(LcovException::class);
    Report::fromCoverage('TN:Example');
  }

  /**
   * @test Report::fromJson
   */
  public function testFromJson(): void {
    // It should return a null reference with a non-object value.
    assertThat(Report::fromJson('foo'), isNull());

    // It should return an instance with default values for an empty map.
    $report = Report::fromJson([]);
    assertThat($report, isInstanceOf(Report::class));
    assertThat($report->getRecords(), isEmpty());
    assertThat($report->getTestName(), isEmpty());

    // It should return an initialized instance for a non-empty map.
    $report = Report::fromJson([
      'records' => [[]],
      'testName' => 'LcovTest'
    ]);

    assertThat($report, isInstanceOf(Report::class));
    assertThat($report->getTestName(), equalTo('LcovTest'));

    $records = $report->getRecords();
    assertThat($records, countOf(1));
    assertThat($records[0], isInstanceOf(Record::class));
    assertThat($report->getTestName(), equalTo('LcovTest'));
  }

  /**
   * @test Report::jsonSerialize
   */
  public function testJsonSerialize(): void {
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

  /**
   * @test Report::__toString
   */
  public function testToString(): void {
    // It should return a format like "TN:<testName>".
    assertThat((string) new Report, isEmpty());

    $record = new Record('');
    assertThat((string) new Report('LcovTest', [$record]), equalTo(str_replace('{{eol}}', PHP_EOL, "TN:LcovTest{{eol}}$record")));
  }
}
