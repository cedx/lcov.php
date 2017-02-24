<?php
/**
 * Implementation of the `lcov\test\ReportTest` class.
 */
namespace lcov\test;

use Codeception\{Specify};
use lcov\{BranchData, FunctionData, LineData, Record, Report};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\Report
 */
class ReportTest extends TestCase {
  use Specify;

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->specify('should return a null reference with a non-object value', function() {
      $this->assertNull(Report::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $report = Report::fromJSON([]);
      $this->assertInstanceOf(Report::class, $report);
      $this->assertCount(0, $report->getRecords());
      $this->assertEmpty($report->getTestName());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $report = Report::fromJSON([
        'records' => [[]],
        'testName' => 'LcovTest'
      ]);

      $this->assertInstanceOf(Report::class, $report);
      $this->assertEquals('LcovTest', $report->getTestName());

      $records = $report->getRecords();
      $this->assertCount(1, $records);
      $this->assertInstanceOf(Record::class, $records[0]);
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $map = (new Report())->jsonSerialize();
      $this->assertCount(2, get_object_vars($map));
      $this->assertCount(0, $map->records);
      $this->assertEmpty($map->testName);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $map = (new Report('LcovTest', [new Record()]))->jsonSerialize();
      $this->assertCount(2, get_object_vars($map));
      $this->assertCount(1, $map->records);
      $this->assertInstanceOf(\stdClass::class, $map->records[0]);
      $this->assertEquals('LcovTest', $map->testName);
    });
  }

  /**
   * @test ::parse
   */
  public function testParse() {
    $report = Report::parse(@file_get_contents(__DIR__.'/fixtures/lcov.info'));
    $records = $report->getRecords();

    $this->specify('should have a test name', function() use ($report) {
      $this->assertEquals('Example', $report->getTestName());
    });

    $this->specify('should contain three records', function() use ($records) {
      $this->assertCount(3, $records);
      $this->assertInstanceOf(Record::class, $records[0]);
      $this->assertEquals('/home/cedx/lcov.php/fixture.php', $records[0]->getSourceFile());
      $this->assertEquals('/home/cedx/lcov.php/func1.php', $records[1]->getSourceFile());
      $this->assertEquals('/home/cedx/lcov.php/func2.php', $records[2]->getSourceFile());
    });

    $this->specify('should have detailed branch coverage', function() use ($records) {
      $branches = $records[1]->getBranches();
      $this->assertEquals(4, $branches->getFound());
      $this->assertEquals(4, $branches->getHit());

      $data = $branches->getData();
      $this->assertCount(4, $data);
      $this->assertInstanceOf(BranchData::class, $data[0]);
      $this->assertEquals(8, $data[0]->getLineNumber());
    });

    $this->specify('should have detailed function coverage', function() use ($records) {
      $functions = $records[1]->getFunctions();
      $this->assertEquals(1, $functions->getFound());
      $this->assertEquals(1, $functions->getHit());

      $data = $functions->getData();
      $this->assertCount(1, $data);
      $this->assertInstanceOf(FunctionData::class, $data[0]);
      $this->assertEquals('func1', $data[0]->getFunctionName());
    });

    $this->specify('should have detailed line coverage', function() use ($records) {
      $lines = $records[1]->getLines();
      $this->assertEquals(9, $lines->getFound());
      $this->assertEquals(9, $lines->getHit());

      $data = $lines->getData();
      $this->assertCount(9, $data);
      $this->assertInstanceOf(LineData::class, $data[0]);
      $this->assertEquals('5kX7OTfHFcjnS98fjeVqNA', $data[0]->getChecksum());
    });

    $this->specify('should throw an error if the input is invalid', function() {
      $this->expectException(\UnexpectedValueException::class);
      Report::parse('TN:Example');
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->specify('should return a format like "TN:<testName>"', function() {
      $this->assertEmpty((string) new Report());

      $record = new Record();
      $this->assertEquals(str_replace('{{eol}}', PHP_EOL, "TN:LcovTest{{eol}}$record"), (string) new Report('LcovTest', [$record]));
    });
  }
}
