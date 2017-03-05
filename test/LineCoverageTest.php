<?php
/**
 * Implementation of the `lcov\test\LineCoverageTest` class.
 */
namespace lcov\test;

use Codeception\{Specify};
use lcov\{LineCoverage, LineData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\LineCoverage
 */
class LineCoverageTest extends TestCase {
  use Specify;

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->specify('should return a null reference with a non-object value', function() {
      static::assertNull(LineCoverage::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $coverage = LineCoverage::fromJSON([]);
      static::assertInstanceOf(LineCoverage::class, $coverage);
      static::assertCount(0, $coverage->getData());
      static::assertEquals(0, $coverage->getFound());
      static::assertEquals(0, $coverage->getHit());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $coverage = LineCoverage::fromJSON(['data' => [['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
      static::assertInstanceOf(LineCoverage::class, $coverage);

      $entries = $coverage->getData();
      static::assertCount(1, $entries);
      static::assertInstanceOf(LineData::class, $entries[0]);
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
      $map = (new LineCoverage())->jsonSerialize();
      static::assertCount(3, get_object_vars($map));
      static::assertCount(0, $map->data);
      static::assertEquals(0, $map->found);
      static::assertEquals(0, $map->hit);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $map = (new LineCoverage(23, 11, [new LineData()]))->jsonSerialize();
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
    $this->specify('should return a format like "LF:<found>\\n,LH:<hit>"', function() {
      static::assertEquals(str_replace('{{eol}}', PHP_EOL, 'LF:0{{eol}}LH:0'), (string) new LineCoverage());

      $data = new LineData(127, 3);
      $coverage = new LineCoverage(23, 11, [$data]);
      static::assertEquals(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}LF:23{{eol}}LH:11"), (string) $coverage);
    });
  }
}
