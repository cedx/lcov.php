<?php
/**
 * Implementation of the `lcov\test\BranchDataTest` class.
 */
namespace lcov\test;
use lcov\{BranchData};

/**
 * Tests the features of the `lcov\BranchData` class.
 */
class BranchDataTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `BranchData` constructor.
   */
  public function testConstructor() {
    $data = new BranchData([
      'blockNumber' => 3,
      'branchNumber' => 2,
      'lineNumber' => 127,
      'taken' => 1
    ]);

    $this->assertEquals(3, $data->getBlockNumber());
    $this->assertEquals(2, $data->getBranchNumber());
    $this->assertEquals(127, $data->getLineNumber());
    $this->assertEquals(1, $data->getTaken());
  }

  /**
   * Tests the `BranchData::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(BranchData::fromJSON('foo'));

    $data = BranchData::fromJSON([]);
    $this->assertEquals(0, $data->getBlockNumber());
    $this->assertEquals(0, $data->getBranchNumber());
    $this->assertEquals(0, $data->getLineNumber());
    $this->assertEquals(0, $data->getTaken());

    $data = BranchData::fromJSON(['branch' => 2, 'block' => 3, 'line' => 127, 'taken' => 1]);
    $this->assertEquals(3, $data->getBlockNumber());
    $this->assertEquals(2, $data->getBranchNumber());
    $this->assertEquals(127, $data->getLineNumber());
    $this->assertEquals(1, $data->getTaken());
  }

  /**
   * Tests the `BranchData::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $data = (new BranchData())->jsonSerialize();
    $this->assertEquals(count(get_object_vars($data)), 4);
    $this->assertEquals(0, $data->block);
    $this->assertEquals(0, $data->branch);
    $this->assertEquals(0, $data->line);
    $this->assertEquals(0, $data->taken);

    $data = (new BranchData([
      'blockNumber' => 3,
      'branchNumber' => 2,
      'lineNumber' => 127,
      'taken' => 1
    ]))->jsonSerialize();

    $this->assertEquals(count(get_object_vars($data)), 4);
    $this->assertEquals(3, $data->block);
    $this->assertEquals(2, $data->branch);
    $this->assertEquals(127, $data->line);
    $this->assertEquals(1, $data->taken);
  }

  /**
   * Tests the `BranchData::__toString()` method.
   */
  public function testToString() {
    $data = new BranchData();
    $this->assertEquals('BRDA:0,0,0,-', (string) $data);

    $data = new BranchData(['blockNumber' => 3, 'branchNumber' => 2, 'lineNumber' => 127, 'taken' => 1]);
    $this->assertEquals('BRDA:127,3,2,1', (string) $data);
  }
}
