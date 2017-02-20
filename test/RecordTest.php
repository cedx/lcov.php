<?php
/**
 * Implementation of the `lcov\test\RecordTest` class.
 */
namespace lcov\test;

use lcov\{BranchCoverage, FunctionCoverage, LineCoverage, Record};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\Record
 */
class RecordTest extends TestCase {

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    // Should return a null reference with a non-object value.
    $this->assertNull(Record::fromJSON('foo'));

    // Should return an instance with default values for an empty map.
    $record = Record::fromJSON([]);
    $this->assertInstanceOf(Record::class, $record);
    $this->assertNull($record->getBranches());
    $this->assertNull($record->getFunctions());
    $this->assertNull($record->getLines());
    $this->assertEmpty($record->getSourceFile());

    // Should return an initialized instance for a non-empty map.
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
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    // Should return a map with default values for a newly created instance.
    $map = (new Record())->jsonSerialize();
    $this->assertCount(4, get_object_vars($map));
    $this->assertNull($map->branches);
    $this->assertNull($map->functions);
    $this->assertNull($map->lines);
    $this->assertEmpty($map->sourceFile);

    // Should return a non-empty map for an initialized instance.
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
   * @test ::__toString
   */
  public function testToString() {
    // Should return a format like "SF:<sourceFile>\\n,end_of_record".
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'SF:{{eol}}end_of_record'), (string) new Record());

    $record = (new Record('/home/cedx/lcov.php'))
      ->setBranches(new BranchCoverage())
      ->setFunctions(new FunctionCoverage())
      ->setLines(new LineCoverage());

    $format = "SF:/home/cedx/lcov.php{{eol}}{$record->getFunctions()}{{eol}}{$record->getBranches()}{{eol}}{$record->getLines()}{{eol}}end_of_record";
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, $format), (string) $record);
  }
}
