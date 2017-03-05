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
      static::assertNull(Report::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $report = Report::fromJSON([]);
      static::assertInstanceOf(Report::class, $report);
      static::assertCount(0, $report->getRecords());
      static::assertEmpty($report->getTestName());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $report = Report::fromJSON([
        'records' => [[]],
        'testName' => 'LcovTest'
      ]);

      static::assertInstanceOf(Report::class, $report);
      static::assertEquals('LcovTest', $report->getTestName());

      $records = $report->getRecords();
      static::assertCount(1, $records);
      static::assertInstanceOf(Record::class, $records[0]);
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $map = (new Report())->jsonSerialize();
      static::assertCount(2, get_object_vars($map));
      static::assertCount(0, $map->records);
      static::assertEmpty($map->testName);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $map = (new Report('LcovTest', [new Record()]))->jsonSerialize();
      static::assertCount(2, get_object_vars($map));
      static::assertCount(1, $map->records);
      static::assertInstanceOf(\stdClass::class, $map->records[0]);
      static::assertEquals('LcovTest', $map->testName);
    });
  }

  /**
   * @test ::parse
   */
  public function testParse() {
    $report = Report::parse(@file_get_contents(__DIR__.'/fixtures/lcov.info'));
    $records = $report->getRecords();

    $this->specify('should have a test name', function() use ($report) {
      static::assertEquals('Example', $report->getTestName());
    });

    $this->specify('should contain three records', function() use ($records) {
      static::assertCount(3, $records);
      static::assertInstanceOf(Record::class, $records[0]);
      static::assertEquals('/home/cedx/lcov.php/fixture.php', $records[0]->getSourceFile());
      static::assertEquals('/home/cedx/lcov.php/func1.php', $records[1]->getSourceFile());
      static::assertEquals('/home/cedx/lcov.php/func2.php', $records[2]->getSourceFile());
    });

    $this->specify('should have detailed branch coverage', function() use ($records) {
      $branches = $records[1]->getBranches();
      static::assertEquals(4, $branches->getFound());
      static::assertEquals(4, $branches->getHit());

      $data = $branches->getData();
      static::assertCount(4, $data);
      static::assertInstanceOf(BranchData::class, $data[0]);
      static::assertEquals(8, $data[0]->getLineNumber());
    });

    $this->specify('should have detailed function coverage', function() use ($records) {
      $functions = $records[1]->getFunctions();
      static::assertEquals(1, $functions->getFound());
      static::assertEquals(1, $functions->getHit());

      $data = $functions->getData();
      static::assertCount(1, $data);
      static::assertInstanceOf(FunctionData::class, $data[0]);
      static::assertEquals('func1', $data[0]->getFunctionName());
    });

    $this->specify('should have detailed line coverage', function() use ($records) {
      $lines = $records[1]->getLines();
      static::assertEquals(9, $lines->getFound());
      static::assertEquals(9, $lines->getHit());

      $data = $lines->getData();
      static::assertCount(9, $data);
      static::assertInstanceOf(LineData::class, $data[0]);
      static::assertEquals('5kX7OTfHFcjnS98fjeVqNA', $data[0]->getChecksum());
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
      static::assertEmpty((string) new Report());

      $record = new Record();
      static::assertEquals(str_replace('{{eol}}', PHP_EOL, "TN:LcovTest{{eol}}$record"), (string) new Report('LcovTest', [$record]));
    });
  }
}
