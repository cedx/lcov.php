<?php
/**
 * Implementation of the `lcov\test\RecordTest` class.
 */
namespace lcov\test;
use lcov\{BranchCoverage, FunctionCoverage, LineCoverage, Record};

/**
 * Tests the features of the `lcov\Record` class.
 */
class RecordTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `Record` constructor.
   */
  public function testConstructor() {
    $record = new Record();
    $this->assertNull($record->getBranches());
    $this->assertNull($record->getFunctions());
    $this->assertNull($record->getLines());
    $this->assertEquals('', $record->getSourceFile());

    $record = new Record([
      'branches' => $branches = new BranchCoverage(),
      'functions' => $functions = new FunctionCoverage(),
      'lines' => $lines = new LineCoverage(),
      'sourceFile' => '/home/cedx/lcov.php'
    ]);

    $this->assertSame($branches, $record->getBranches());
    $this->assertSame($functions, $record->getFunctions());
    $this->assertSame($lines, $record->getLines());
    $this->assertEquals('/home/cedx/lcov.php', $record->getSourceFile());
  }

  /**
   * Tests the `Record::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(Record::fromJSON('foo'));

    $record = Record::fromJSON([]);
    $this->assertInstanceOf(Record::class, $record);
    $this->assertNull($record->getBranches());
    $this->assertNull($record->getFunctions());
    $this->assertNull($record->getLines());
    $this->assertEquals('', $record->getSourceFile());

    $record = Record::fromJSON([
      'branches' => [],
      'functions' => [],
      'lines' => [],
      'sourceFile' => '/home/cedx/lcov.php'
    ]);

    $this->assertInstanceOf(Record::class, $record);
    $this->assertInstanceOf(BranchCoverage::class, $record->getBranches());
    $this->assertInstanceOf(FunctionCoverage::class, $record->getFunctions());
    $this->assertInstanceOf(LineCoverage::class, $record->getLines());
    $this->assertEquals('/home/cedx/lcov.php', $record->getSourceFile());
  }

  /**
   * Tests the `Record::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $map = (new Record())->jsonSerialize();
    $this->assertCount(4, get_object_vars($map));
    $this->assertNull($map->branches);
    $this->assertNull($map->functions);
    $this->assertNull($map->lines);
    $this->assertEquals('', $map->sourceFile);

    $map = (new Record([
      'branches' => new BranchCoverage(),
      'functions' => new FunctionCoverage(),
      'lines' => new LineCoverage(),
      'sourceFile' => '/home/cedx/lcov.php'
    ]))->jsonSerialize();

    $this->assertCount(4, get_object_vars($map));
    $this->assertInstanceOf(\stdClass::class, $map->branches);
    $this->assertInstanceOf(\stdClass::class, $map->functions);
    $this->assertInstanceOf(\stdClass::class, $map->lines);
    $this->assertEquals('/home/cedx/lcov.php', $map->sourceFile);
  }

  /**
   * Tests the `Record::__toString()` method.
   */
  public function testToString() {
    $record = new Record();
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'SF:{{eol}}end_of_record'), (string) $record);

    $record = new Record([
      'branches' => $branches = new BranchCoverage(),
      'functions' => $functions = new FunctionCoverage(),
      'lines' => $lines = new LineCoverage(),
      'sourceFile' => '/home/cedx/lcov.php'
    ]);

    $format = "SF:/home/cedx/lcov.php{{eol}}$functions{{eol}}$branches{{eol}}$lines{{eol}}end_of_record";
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, $format), (string) $record);
  }
}
