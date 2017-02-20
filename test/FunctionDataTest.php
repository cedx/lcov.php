<?php
/**
 * Implementation of the `lcov\test\FunctionDataTest` class.
 */
namespace lcov\test;

use lcov\{FunctionData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\FunctionData
 */
class FunctionDataTest extends TestCase {

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    // Should return a null reference with a non-object value.
    $this->assertNull(FunctionData::fromJSON('foo'));

    // Should return an instance with default values for an empty map.
    $data = FunctionData::fromJSON([]);
    $this->assertInstanceOf(FunctionData::class, $data);
    $this->assertEquals(0, $data->getExecutionCount());
    $this->assertEmpty($data->getFunctionName());
    $this->assertEquals(0, $data->getLineNumber());

    // Should return an initialized instance for a non-empty map.
    $data = FunctionData::fromJSON(['executionCount' => 3, 'functionName' => 'main', 'lineNumber' => 127]);
    $this->assertInstanceOf(FunctionData::class, $data);
    $this->assertEquals(3, $data->getExecutionCount());
    $this->assertEquals('main', $data->getFunctionName());
    $this->assertEquals(127, $data->getLineNumber());
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    // Should return a map with default values for a newly created instance.
    $data = (new FunctionData())->jsonSerialize();
    $this->assertCount(3, get_object_vars($data));
    $this->assertEquals(0, $data->executionCount);
    $this->assertEmpty($data->functionName);
    $this->assertEquals(0, $data->lineNumber);

    // Should return a non-empty map for an initialized instance.
    $data = (new FunctionData('main', 127, 3))->jsonSerialize();
    $this->assertCount(3, get_object_vars($data));
    $this->assertEquals(3, $data->executionCount);
    $this->assertEquals('main', $data->functionName);
    $this->assertEquals(127, $data->lineNumber);
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    // Should return a format like "FN:<lineNumber>,<functionName>" when used as definition.
    $data = new FunctionData();
    $this->assertEquals('FNDA:0,', $data->toString(false));
    $this->assertEquals('FN:0,', $data->toString(true));

    // Should return a format like "FNDA:<executionCount>,<functionName>" when used as data.
    $data = new FunctionData('main', 127, 3);
    $this->assertEquals('FNDA:3,main', $data->toString(false));
    $this->assertEquals('FN:127,main', $data->toString(true));
  }
}
