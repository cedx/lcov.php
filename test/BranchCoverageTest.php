<?php
/**
 * Implementation of the `lcov\test\BranchCoverageTest` class.
 */
namespace lcov\test;

use Codeception\{Specify};
use lcov\{BranchCoverage, BranchData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\BranchCoverage
 */
class BranchCoverageTest extends TestCase {
  use Specify;

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->specify('should return a null reference with a non-object value', function() {
      static::assertNull(BranchCoverage::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $coverage = BranchCoverage::fromJSON([]);
      static::assertInstanceOf(BranchCoverage::class, $coverage);
      static::assertCount(0, $coverage->getData());
      static::assertEquals(0, $coverage->getFound());
      static::assertEquals(0, $coverage->getHit());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $coverage = BranchCoverage::fromJSON(['data' => [['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
      static::assertInstanceOf(BranchCoverage::class, $coverage);

      $entries = $coverage->getData();
      static::assertCount(1, $entries);
      static::assertInstanceOf(BranchData::class, $entries[0]);
      static::assertEquals(127, $entries[0]->getLineNumber());

      static::assertEquals(23, $coverage->getFound());
      static::assertEquals(11, $coverage->getHit());
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $map = (new BranchCoverage())->jsonSerialize();
      static::assertCount(3, get_object_vars($map));
      static::assertCount(0, $map->data);
      static::assertEquals(0, $map->found);
      static::assertEquals(0, $map->hit);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $map = (new BranchCoverage(23, 11, [new BranchData()]))->jsonSerialize();
      static::assertCount(3, get_object_vars($map));
      static::assertCount(1, $map->data);
      static::assertInstanceOf(\stdClass::class, $map->data[0]);
      static::assertObjectHasAttribute('lineNumber', $map->data[0]);
      static::assertEquals(23, $map->found);
      static::assertEquals(11, $map->hit);
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->specify('should return a format like "BRF:<found>\\n,BRH:<hit>"', function() {
      static::assertEquals(str_replace('{{eol}}', PHP_EOL, 'BRF:0{{eol}}BRH:0'), (string) new BranchCoverage());

      $data = new BranchData(127, 3, 2);
      $coverage = new BranchCoverage(23, 11, [$data]);
      static::assertEquals(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}BRF:23{{eol}}BRH:11"), (string) $coverage);
    });
  }
}
