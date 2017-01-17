<?php
/**
 * Implementation of the `lcov\test\FunctionCoverageTest` class.
 */
namespace lcov\test;
use lcov\{FunctionCoverage, FunctionData};

/**
 * Tests the features of the `lcov\FunctionCoverage` class.
 */
class FunctionCoverageTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `FunctionCoverage` constructor.
   */
  public function testConstructor() {
    $data = new FunctionData();
    $coverage = new FunctionCoverage([
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
   * Tests the `FunctionCoverage::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(FunctionCoverage::fromJSON('foo'));

    $coverage = FunctionCoverage::fromJSON([]);
    $this->assertInstanceOf(FunctionCoverage::class, $coverage);
    $this->assertCount(0, $coverage->getData());
    $this->assertEquals(0, $coverage->getFound());
    $this->assertEquals(0, $coverage->getHit());

    $coverage = FunctionCoverage::fromJSON(['data' => [['line' => 127]], 'found' => 23, 'hit' => 11]);
    $this->assertInstanceOf(FunctionCoverage::class, $coverage);

    $entries = $coverage->getData();
    $this->assertCount(1, $entries);
    $this->assertInstanceOf(FunctionData::class, $entries[0]);
    $this->assertEquals(127, $entries[0]->getLineNumber());

    $this->assertEquals(23, $coverage->getFound());
    $this->assertEquals(11, $coverage->getHit());
  }

  /**
   * Tests the `FunctionCoverage::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $map = (new FunctionCoverage())->jsonSerialize();
    $this->assertCount(3, get_object_vars($map));
    $this->assertCount(0, $map->data);
    $this->assertEquals(0, $map->found);
    $this->assertEquals(0, $map->hit);

    $map = (new FunctionCoverage([
      'data' => [new FunctionData()],
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
   * Tests the `FunctionCoverage::__toString()` method.
   */
  public function testToString() {
    $coverage = new FunctionCoverage();
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'FNF:0{{eol}}FNH:0'), (string) $coverage);

    $data = new FunctionData([
      'executionCount' => 3,
      'functionName' => 'main',
      'lineNumber' => 127
    ]);

    $coverage = new FunctionCoverage(['data' => [$data], 'found' => 23, 'hit' => 11]);
    $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'FN:127,main{{eol}}FNDA:3,main{{eol}}FNF:23{{eol}}FNH:11'), (string) $coverage);
  }
}
