<?php
/**
 * Implementation of the `lcov\test\FunctionDataTest` class.
 */
namespace lcov\test;

use lcov\{FunctionData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\FunctionData` class.
 */
class FunctionDataTest extends TestCase {

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->assertNull(FunctionData::fromJSON('foo'));

    $data = FunctionData::fromJSON([]);
    $this->assertInstanceOf(FunctionData::class, $data);
    $this->assertEquals(0, $data->getExecutionCount());
    $this->assertEmpty($data->getFunctionName());
    $this->assertEquals(0, $data->getLineNumber());

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
    $data = (new FunctionData())->jsonSerialize();
    $this->assertCount(3, get_object_vars($data));
    $this->assertEquals(0, $data->executionCount);
    $this->assertEmpty($data->functionName);
    $this->assertEquals(0, $data->lineNumber);

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
    $data = new FunctionData();
    $this->assertEquals('FNDA:0,', $data->toString(false));
    $this->assertEquals('FN:0,', $data->toString(true));

    $data = new FunctionData('main', 127, 3);
    $this->assertEquals('FNDA:3,main', $data->toString(false));
    $this->assertEquals('FN:127,main', $data->toString(true));
  }
}
