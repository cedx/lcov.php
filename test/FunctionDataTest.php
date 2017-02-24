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
      $this->assertNull(FunctionData::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $data = FunctionData::fromJSON([]);
      $this->assertInstanceOf(FunctionData::class, $data);
      $this->assertEquals(0, $data->getExecutionCount());
      $this->assertEmpty($data->getFunctionName());
      $this->assertEquals(0, $data->getLineNumber());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $data = FunctionData::fromJSON(['executionCount' => 3, 'functionName' => 'main', 'lineNumber' => 127]);
      $this->assertInstanceOf(FunctionData::class, $data);
      $this->assertEquals(3, $data->getExecutionCount());
      $this->assertEquals('main', $data->getFunctionName());
      $this->assertEquals(127, $data->getLineNumber());
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $data = (new FunctionData())->jsonSerialize();
      $this->assertCount(3, get_object_vars($data));
      $this->assertEquals(0, $data->executionCount);
      $this->assertEmpty($data->functionName);
      $this->assertEquals(0, $data->lineNumber);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $data = (new FunctionData('main', 127, 3))->jsonSerialize();
      $this->assertCount(3, get_object_vars($data));
      $this->assertEquals(3, $data->executionCount);
      $this->assertEquals('main', $data->functionName);
      $this->assertEquals(127, $data->lineNumber);
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->specify('should return a format like "FN:<lineNumber>,<functionName>" when used as definition', function() {
      $data = new FunctionData();
      $this->assertEquals('FNDA:0,', $data->toString(false));
      $this->assertEquals('FN:0,', $data->toString(true));
    });

    $this->specify('should return a format like "FNDA:<executionCount>,<functionName>" when used as data', function() {
      $data = new FunctionData('main', 127, 3);
      $this->assertEquals('FNDA:3,main', $data->toString(false));
      $this->assertEquals('FN:127,main', $data->toString(true));
    });
  }
}
