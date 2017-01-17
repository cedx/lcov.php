<?php
/**
 * Implementation of the `lcov\test\LineCoverageTest` class.
 */
namespace lcov\test;
use lcov\{LineCoverage, LineData};

/**
 * Tests the features of the `lcov\LineCoverage` class.
 */
class LineCoverageTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `LineCoverage` constructor.
   */
  public function testConstructor() {
    $data = new LineData();
    $coverage = new LineCoverage([
      'data' => [$data],
      'found' => 23,
      'hit' => 11
    ]);

    $entries = $coverage->getData();
    $this->assertCount(1, $entries);
    $this->assertSame($data, $entries[0]);

    $this->assertEquals(23, $coverage->getFound());
    $this->assertEquals(11, $coverage->getHit());
  }

  /**
   * Tests the `LineCoverage::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(LineCoverage::fromJSON('foo'));

    $coverage = LineCoverage::fromJSON([]);
    $this->assertInstanceOf(LineCoverage::class, $coverage);
    $this->assertCount(0, $coverage->getData());
    $this->assertEquals(0, $coverage->getFound());
    $this->assertEquals(0, $coverage->getHit());

    $coverage = LineCoverage::fromJSON(['data' => [['line' => 127]], 'found' => 23, 'hit' => 11]);
    $this->assertInstanceOf(LineCoverage::class, $coverage);

    $entries = $coverage->getData();
    $this->assertCount(1, $entries);
    $this->assertInstanceOf(LineData::class, $entries[0]);
    $this->assertEquals(127, $entries[0]->getLineNumber());

    $this->assertEquals(23, $coverage->getFound());
    $this->assertEquals(11, $coverage->getHit());
  }

  /**
   * Tests the `LineCoverage::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $map = (new LineCoverage())->jsonSerialize();
    $this->assertCount(3, get_object_vars($map));
    $this->assertCount(0, $map->data);
    $this->assertEquals(0, $map->found);
    $this->assertEquals(0, $map->hit);

    $map = (new LineCoverage([
      'data' => [new LineData()],
      'found' => 23,
      'hit' => 11
    ]))->jsonSerialize();

    $this->assertCount(3, get_object_vars($map));
    $this->assertCount(1, $map->data);
    $this->assertInstanceOf(\stdClass::class, $map->data[0]);
    $this->assertObjectHasAttribute('line', $map->data[0]);
    $this->assertEquals(23, $map->found);
    $this->assertEquals(11, $map->hit);
  }

  /**
   * Tests the `LineCoverage::__toString()` method.
   */
  public function testToString() {
    $coverage = new LineCoverage();
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'LF:0{{eol}}LH:0'), (string) $coverage);

    $data = new LineData([
      'executionCount' => 3,
      'lineNumber' => 127
    ]);

    $coverage = new LineCoverage(['data' => [$data], 'found' => 23, 'hit' => 11]);
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'DA:127,3{{eol}}LF:23{{eol}}LH:11'), (string) $coverage);
  }
}
