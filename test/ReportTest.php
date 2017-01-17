<?php
/**
 * Implementation of the `lcov\test\ReportTest` class.
 */
namespace lcov\test;
use lcov\{Report};

/**
 * Tests the features of the `lcov\Report` class.
 */
class ReportTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `Report` constructor.
   */
  public function testConstructor() {
    $record = new Report();
    $this->assertNull($record->getReports());
    $this->assertNull($record->getTestName());

    $record = new Report([
      'records' => $records = new BranchCoverage(),
      'testName' => $testName = new FunctionCoverage()
    ]);

    $this->assertSame($records, $record->getReports());
    $this->assertSame($testName, $record->getTestName());
  }

  /**
   * Tests the `Report::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(Report::fromJSON('foo'));

    $record = Report::fromJSON([]);
    $this->assertInstanceOf(Report::class, $record);
    $this->assertNull($record->getReports());
    $this->assertNull($record->getTestName());

    $record = Report::fromJSON([
      'records' => [],
      'testName' => []
    ]);

    $this->assertInstanceOf(Report::class, $record);
    $this->assertInstanceOf(BranchCoverage::class, $record->getReports());
    $this->assertEquals('/home/cedx/lcov.php', $record->getSourceFile());
  }

  /**
   * Tests the `Report::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $map = (new Report())->jsonSerialize();
    $this->assertCount(4, get_object_vars($map));
    $this->assertNull($map->records);
    $this->assertNull($map->testName);

    $map = (new Report([
      'records' => new BranchCoverage(),
      'testName' => new FunctionCoverage()
    ]))->jsonSerialize();

    $this->assertCount(4, get_object_vars($map));
    $this->assertInstanceOf(\stdClass::class, $map->records);
    $this->assertEquals('/home/cedx/lcov.php', $map->file);
  }

  /**
   * Tests the `Report::__toString()` method.
   */
  public function testToString() {
    $record = new Report();
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'SF:{{eol}}end_of_record'), (string) $record);

    $record = new Report([
      'records' => $records = new BranchCoverage(),
      'testName' => $testName = new FunctionCoverage()
    ]);

    $format = 'SF:/home/cedx/lcov.php{{eol}}FNF:0{{eol}}FNH:0{{eol}}BRF:0{{eol}}BRH:0{{eol}}LF:0{{eol}}LH:0{{eol}}end_of_record';
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, $format), (string) $record);
  }
}
