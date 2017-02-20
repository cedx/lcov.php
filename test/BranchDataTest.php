<?php
/**
 * Implementation of the `lcov\test\BranchDataTest` class.
 */
namespace lcov\test;

use lcov\{BranchData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\BranchData
 */
class BranchDataTest extends TestCase {

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    // Should return a null reference with a non-object value.
    $this->assertNull(BranchData::fromJSON('foo'));

    // Should return an instance with default values for an empty map.
    $data = BranchData::fromJSON([]);
    $this->assertInstanceOf(BranchData::class, $data);
    $this->assertEquals(0, $data->getBlockNumber());
    $this->assertEquals(0, $data->getBranchNumber());
    $this->assertEquals(0, $data->getLineNumber());
    $this->assertEquals(0, $data->getTaken());

    // Should return an initialized instance for a non-empty map.
    $data = BranchData::fromJSON(['blockNumber' => 3, 'branchNumber' => 2, 'lineNumber' => 127, 'taken' => 1]);
    $this->assertInstanceOf(BranchData::class, $data);
    $this->assertEquals(3, $data->getBlockNumber());
    $this->assertEquals(2, $data->getBranchNumber());
    $this->assertEquals(127, $data->getLineNumber());
    $this->assertEquals(1, $data->getTaken());
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    // Should return a map with default values for a newly created instance.
    $data = (new BranchData())->jsonSerialize();
    $this->assertCount(4, get_object_vars($data));
    $this->assertEquals(0, $data->blockNumber);
    $this->assertEquals(0, $data->branchNumber);
    $this->assertEquals(0, $data->lineNumber);
    $this->assertEquals(0, $data->taken);

    // Should return a non-empty map for an initialized instance.
    $data = (new BranchData(127, 3, 2, 1))->jsonSerialize();
    $this->assertCount(4, get_object_vars($data));
    $this->assertEquals(3, $data->blockNumber);
    $this->assertEquals(2, $data->branchNumber);
    $this->assertEquals(127, $data->lineNumber);
    $this->assertEquals(1, $data->taken);
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    // Should return a format like "BRDA:<lineNumber>,<blockNumber>,<branchNumber>,<taken>".
    $this->assertEquals('BRDA:0,0,0,-', (string) new BranchData());
    $this->assertEquals('BRDA:127,3,2,1', (string) new BranchData(127, 3, 2, 1));
  }
}
