<?php
/**
 * Implementation of the `lcov\test\RecordTest` class.
 */
namespace lcov\test;

use Codeception\{Specify};
use lcov\{BranchCoverage, FunctionCoverage, LineCoverage, Record};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\Record
 */
class RecordTest extends TestCase {
  use Specify;

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->specify('should return a null reference with a non-object value', function() {
      static::assertNull(Record::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $record = Record::fromJSON([]);
      static::assertInstanceOf(Record::class, $record);
      static::assertNull($record->getBranches());
      static::assertNull($record->getFunctions());
      static::assertNull($record->getLines());
      static::assertEmpty($record->getSourceFile());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $record = Record::fromJSON([
        'branches' => [],
        'functions' => [],
        'lines' => [],
        'sourceFile' => '/home/cedx/lcov.php'
      ]);

      static::assertInstanceOf(Record::class, $record);
      static::assertInstanceOf(BranchCoverage::class, $record->getBranches());
      static::assertInstanceOf(FunctionCoverage::class, $record->getFunctions());
      static::assertInstanceOf(LineCoverage::class, $record->getLines());
      static::assertEquals('/home/cedx/lcov.php', $record->getSourceFile());
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $map = (new Record())->jsonSerialize();
      static::assertCount(4, get_object_vars($map));
      static::assertNull($map->branches);
      static::assertNull($map->functions);
      static::assertNull($map->lines);
      static::assertEmpty($map->sourceFile);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $map = (new Record('/home/cedx/lcov.php'))
        ->setBranches(new BranchCoverage())
        ->setFunctions(new FunctionCoverage())
        ->setLines(new LineCoverage())
        ->jsonSerialize();

      static::assertCount(4, get_object_vars($map));
      static::assertInstanceOf(\stdClass::class, $map->branches);
      static::assertInstanceOf(\stdClass::class, $map->functions);
      static::assertInstanceOf(\stdClass::class, $map->lines);
      static::assertEquals('/home/cedx/lcov.php', $map->sourceFile);
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->specify('should return a format like "SF:<sourceFile>\\n,end_of_record"', function() {
      static::assertEquals(str_replace('{{eol}}', PHP_EOL, 'SF:{{eol}}end_of_record'), (string) new Record());

      $record = (new Record('/home/cedx/lcov.php'))
        ->setBranches(new BranchCoverage())
        ->setFunctions(new FunctionCoverage())
        ->setLines(new LineCoverage());

      $format = "SF:/home/cedx/lcov.php{{eol}}{$record->getFunctions()}{{eol}}{$record->getBranches()}{{eol}}{$record->getLines()}{{eol}}end_of_record";
      static::assertEquals(str_replace('{{eol}}', PHP_EOL, $format), (string) $record);
    });
  }
}
