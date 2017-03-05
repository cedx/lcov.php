<?php
/**
 * Implementation of the `lcov\test\FunctionDataTest` class.
 */
namespace lcov\test;

use Codeception\{Specify};
use lcov\{FunctionData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\FunctionData
 */
class FunctionDataTest extends TestCase {
  use Specify;

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->specify('should return a null reference with a non-object value', function() {
      static::assertNull(FunctionData::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $data = FunctionData::fromJSON([]);
      static::assertInstanceOf(FunctionData::class, $data);
      static::assertEquals(0, $data->getExecutionCount());
      static::assertEmpty($data->getFunctionName());
      static::assertEquals(0, $data->getLineNumber());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $data = FunctionData::fromJSON(['executionCount' => 3, 'functionName' => 'main', 'lineNumber' => 127]);
      static::assertInstanceOf(FunctionData::class, $data);
      static::assertEquals(3, $data->getExecutionCount());
      static::assertEquals('main', $data->getFunctionName());
      static::assertEquals(127, $data->getLineNumber());
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $data = (new FunctionData())->jsonSerialize();
      static::assertCount(3, get_object_vars($data));
      static::assertEquals(0, $data->executionCount);
      static::assertEmpty($data->functionName);
      static::assertEquals(0, $data->lineNumber);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $data = (new FunctionData('main', 127, 3))->jsonSerialize();
      static::assertCount(3, get_object_vars($data));
      static::assertEquals(3, $data->executionCount);
      static::assertEquals('main', $data->functionName);
      static::assertEquals(127, $data->lineNumber);
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->specify('should return a format like "FN:<lineNumber>,<functionName>" when used as definition', function() {
      $data = new FunctionData();
      static::assertEquals('FNDA:0,', $data->toString(false));
      static::assertEquals('FN:0,', $data->toString(true));
    });

    $this->specify('should return a format like "FNDA:<executionCount>,<functionName>" when used as data', function() {
      $data = new FunctionData('main', 127, 3);
      static::assertEquals('FNDA:3,main', $data->toString(false));
      static::assertEquals('FN:127,main', $data->toString(true));
    });
  }
}
