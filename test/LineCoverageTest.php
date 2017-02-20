<?php
/**
 * Implementation of the `lcov\test\LineCoverageTest` class.
 */
namespace lcov\test;

use lcov\{LineCoverage, LineData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\LineCoverage
 */
class LineCoverageTest extends TestCase {

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    // Should return a null reference with a non-object value.
    $this->assertNull(LineCoverage::fromJSON('foo'));

    // Should return an instance with default values for an empty map.
    $coverage = LineCoverage::fromJSON([]);
    $this->assertInstanceOf(LineCoverage::class, $coverage);
    $this->assertCount(0, $coverage->getData());
    $this->assertEquals(0, $coverage->getFound());
    $this->assertEquals(0, $coverage->getHit());

    // Should return an initialized instance for a non-empty map.
    $coverage = LineCoverage::fromJSON(['data' => [['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
    $this->assertInstanceOf(LineCoverage::class, $coverage);

    $entries = $coverage->getData();
    $this->assertCount(1, $entries);
    $this->assertInstanceOf(LineData::class, $entries[0]);
    $this->assertEquals(127, $entries[0]->getLineNumber());

    $this->assertEquals(23, $coverage->getFound());
    $this->assertEquals(11, $coverage->getHit());
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    // Should return a map with default values for a newly created instance.
    $map = (new LineCoverage())->jsonSerialize();
    $this->assertCount(3, get_object_vars($map));
    $this->assertCount(0, $map->data);
    $this->assertEquals(0, $map->found);
    $this->assertEquals(0, $map->hit);

    // Should return a non-empty map for an initialized instance.
    $map = (new LineCoverage(23, 11, [new LineData()]))->jsonSerialize();
    $this->assertCount(3, get_object_vars($map));
    $this->assertCount(1, $map->data);
    $this->assertInstanceOf(\stdClass::class, $map->data[0]);
    $this->assertObjectHasAttribute('lineNumber', $map->data[0]);
    $this->assertEquals(23, $map->found);
    $this->assertEquals(11, $map->hit);
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    // Should return a format like "LF:<found>\\n,LH:<hit>".
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'LF:0{{eol}}LH:0'), (string) new LineCoverage());

    $data = new LineData(127, 3);
    $coverage = new LineCoverage(23, 11, [$data]);
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}LF:23{{eol}}LH:11"), (string) $coverage);
  }
}
