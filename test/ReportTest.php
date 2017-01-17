<?php
/**
 * Implementation of the `lcov\test\ReportTest` class.
 */
namespace lcov\test;
use lcov\{Record, Report};

/**
 * Tests the features of the `lcov\Report` class.
 */
class ReportTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `Report` constructor.
   */
  public function testConstructor() {
    $report = new Report();
    $this->assertCount(0, $report->getRecords());
    $this->assertEquals('', $report->getTestName());

    $record = new Record();
    $report = new Report([
      'records' => [$record],
      'testName' => 'LcovTest'
    ]);

    $entries = $report->getRecords();
    $this->assertCount(1, $entries);
    $this->assertSame($record, $entries[0]);
    $this->assertEquals('LcovTest', $report->getTestName());
  }

  /**
   * Tests the `Report::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(Report::fromJSON('foo'));

    $report = Report::fromJSON([]);
    $this->assertInstanceOf(Report::class, $report);
    $this->assertCount(0, $report->getRecords());
    $this->assertEquals('', $report->getTestName());

    $report = Report::fromJSON([
      'records' => [[]],
      'testName' => 'LcovTest'
    ]);

    $this->assertInstanceOf(Report::class, $report);
    $this->assertEquals('LcovTest', $report->getTestName());

    $records = $report->getRecords();
    $this->assertCount(1, $records);
    $this->assertInstanceOf(Record::class, $records[0]);
  }

  /**
   * Tests the `Report::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $map = (new Report())->jsonSerialize();
    $this->assertCount(2, get_object_vars($map));
    $this->assertCount(0, $map->records);
    $this->assertEquals('', $map->testName);

    $map = (new Report([
      'records' => [new Record()],
      'testName' => 'LcovTest'
    ]))->jsonSerialize();

    $this->assertCount(2, get_object_vars($map));
    $this->assertCount(1, $map->records);
    $this->assertInstanceOf(\stdClass::class, $map->records[0]);
    $this->assertEquals('LcovTest', $map->testName);
  }

  /**
   * Tests the `Report::parse()` method.
   */
  public function testParse() {
    // TODO
  }

  /**
   * Tests the `Report::__toString()` method.
   */
  public function testToString() {
    $report = new Report();
    $this->assertEquals('TN:', (string) $report);

    $record = new Record();
    $report = new Report([
      'records' => [$record],
      'testName' => 'LcovTest'
    ]);

    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, "TN:LcovTest{{eol}}$record"), (string) $report);
  }
}
