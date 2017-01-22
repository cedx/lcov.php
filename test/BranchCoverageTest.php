<?php
/**
 * Implementation of the `lcov\test\BranchCoverageTest` class.
 */
namespace lcov\test;
use lcov\{BranchCoverage, BranchData};

/**
 * Tests the features of the `lcov\BranchCoverage` class.
 */
class BranchCoverageTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `BranchCoverage::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(BranchCoverage::fromJSON('foo'));

    $coverage = BranchCoverage::fromJSON([]);
    $this->assertInstanceOf(BranchCoverage::class, $coverage);
    $this->assertCount(0, $coverage->getData());
    $this->assertEquals(0, $coverage->getFound());
    $this->assertEquals(0, $coverage->getHit());

    $coverage = BranchCoverage::fromJSON(['data' => [['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
    $this->assertInstanceOf(BranchCoverage::class, $coverage);

    $entries = $coverage->getData();
    $this->assertCount(1, $entries);
    $this->assertInstanceOf(BranchData::class, $entries[0]);
    $this->assertEquals(127, $entries[0]->getLineNumber());

    $this->assertEquals(23, $coverage->getFound());
    $this->assertEquals(11, $coverage->getHit());
  }

  /**
   * Tests the `BranchCoverage::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $map = (new BranchCoverage())->jsonSerialize();
    $this->assertCount(3, get_object_vars($map));
    $this->assertCount(0, $map->data);
    $this->assertEquals(0, $map->found);
    $this->assertEquals(0, $map->hit);

    $map = (new BranchCoverage(23, 11, [new BranchData()]))->jsonSerialize();
    $this->assertCount(3, get_object_vars($map));
    $this->assertCount(1, $map->data);
    $this->assertInstanceOf(\stdClass::class, $map->data[0]);
    $this->assertObjectHasAttribute('lineNumber', $map->data[0]);
    $this->assertEquals(23, $map->found);
    $this->assertEquals(11, $map->hit);
  }

  /**
   * Tests the `BranchCoverage::__toString()` method.
   */
  public function testToString() {
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'BRF:0{{eol}}BRH:0'), (string) new BranchCoverage());

    $data = new BranchData(127, 3, 2);
    $coverage = new BranchCoverage(23, 11, [$data]);
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}BRF:23{{eol}}BRH:11"), (string) $coverage);
  }
}
