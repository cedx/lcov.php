<?php
/**
 * Implementation of the `lcov\test\BranchDataTest` class.
 */
namespace lcov\test;

use Codeception\{Specify};
use lcov\{BranchData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\BranchData
 */
class BranchDataTest extends TestCase {
  use Specify;

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->specify('should return a null reference with a non-object value', function() {
      static::assertNull(BranchData::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $data = BranchData::fromJSON([]);
      static::assertInstanceOf(BranchData::class, $data);
      static::assertEquals(0, $data->getBlockNumber());
      static::assertEquals(0, $data->getBranchNumber());
      static::assertEquals(0, $data->getLineNumber());
      static::assertEquals(0, $data->getTaken());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $data = BranchData::fromJSON(['blockNumber' => 3, 'branchNumber' => 2, 'lineNumber' => 127, 'taken' => 1]);
      static::assertInstanceOf(BranchData::class, $data);
      static::assertEquals(3, $data->getBlockNumber());
      static::assertEquals(2, $data->getBranchNumber());
      static::assertEquals(127, $data->getLineNumber());
      static::assertEquals(1, $data->getTaken());
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $data = (new BranchData())->jsonSerialize();
      static::assertCount(4, get_object_vars($data));
      static::assertEquals(0, $data->blockNumber);
      static::assertEquals(0, $data->branchNumber);
      static::assertEquals(0, $data->lineNumber);
      static::assertEquals(0, $data->taken);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $data = (new BranchData(127, 3, 2, 1))->jsonSerialize();
      static::assertCount(4, get_object_vars($data));
      static::assertEquals(3, $data->blockNumber);
      static::assertEquals(2, $data->branchNumber);
      static::assertEquals(127, $data->lineNumber);
      static::assertEquals(1, $data->taken);
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->specify('should return a format like "BRDA:<lineNumber>,<blockNumber>,<branchNumber>,<taken>"', function() {
      static::assertEquals('BRDA:0,0,0,-', (string) new BranchData());
      static::assertEquals('BRDA:127,3,2,1', (string) new BranchData(127, 3, 2, 1));
    });
  }
}
