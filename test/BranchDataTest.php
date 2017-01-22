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
   * Tests the `BranchData::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(BranchData::fromJSON('foo'));

    $data = BranchData::fromJSON([]);
    $this->assertInstanceOf(BranchData::class, $data);
    $this->assertEquals(0, $data->getBlockNumber());
    $this->assertEquals(0, $data->getBranchNumber());
    $this->assertEquals(0, $data->getLineNumber());
    $this->assertEquals(0, $data->getTaken());

    $data = BranchData::fromJSON(['blockNumber' => 3, 'branchNumber' => 2, 'lineNumber' => 127, 'taken' => 1]);
    $this->assertInstanceOf(BranchData::class, $data);
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
    $this->assertCount(4, get_object_vars($data));
    $this->assertEquals(0, $data->blockNumber);
    $this->assertEquals(0, $data->branchNumber);
    $this->assertEquals(0, $data->lineNumber);
    $this->assertEquals(0, $data->taken);

    $data = (new BranchData(127, 3, 2, 1))->jsonSerialize();
    $this->assertCount(4, get_object_vars($data));
    $this->assertEquals(3, $data->blockNumber);
    $this->assertEquals(2, $data->branchNumber);
    $this->assertEquals(127, $data->lineNumber);
    $this->assertEquals(1, $data->taken);
  }

  /**
   * Tests the `BranchData::__toString()` method.
   */
  public function testToString() {
    $this->assertEquals('BRDA:0,0,0,-', (string) new BranchData());
    $this->assertEquals('BRDA:127,3,2,1', (string) new BranchData(127, 3, 2, 1));
  }
}
