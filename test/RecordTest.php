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
   * Tests the `Record::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(Record::fromJSON('foo'));

    $record = Record::fromJSON([]);
    $this->assertInstanceOf(Record::class, $record);
    $this->assertNull($record->getBranches());
    $this->assertNull($record->getFunctions());
    $this->assertNull($record->getLines());
    $this->assertEmpty($record->getSourceFile());

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
    $this->assertEmpty($map->sourceFile);

    $map = (new Record('/home/cedx/lcov.php'))
      ->setBranches(new BranchCoverage())
      ->setFunctions(new FunctionCoverage())
      ->setLines(new LineCoverage())
      ->jsonSerialize();

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
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'SF:{{eol}}end_of_record'), (string) new Record());

    $record = (new Record('/home/cedx/lcov.php'))
      ->setBranches(new BranchCoverage())
      ->setFunctions(new FunctionCoverage())
      ->setLines(new LineCoverage());

    $format = "SF:/home/cedx/lcov.php{{eol}}{$record->getFunctions()}{{eol}}{$record->getBranches()}{{eol}}{$record->getLines()}{{eol}}end_of_record";
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, $format), (string) $record);
  }
}
