<?php
/**
 * Implementation of the `lcov\test\FunctionDataTest` class.
 */
namespace lcov\test;
use lcov\{FunctionData};

/**
 * Tests the features of the `lcov\FunctionData` class.
 */
class FunctionDataTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `FunctionData` constructor.
   */
  public function testConstructor() {
    $data = new FunctionData([
      'executionCount' => 3,
      'functionName' => 'main',
      'lineNumber' => 127
    ]);

    $this->assertEquals(3, $data->getExecutionCount());
    $this->assertEquals('main', $data->getFunctionName());
    $this->assertEquals(127, $data->getLineNumber());
  }

  /**
   * Tests the `FunctionData::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(FunctionData::fromJSON('foo'));

    $data = FunctionData::fromJSON([]);
    $this->assertEquals(0, $data->getExecutionCount());
    $this->assertEquals('', $data->getFunctionName());
    $this->assertEquals(0, $data->getLineNumber());

    $data = FunctionData::fromJSON(['count' => 3, 'name' => 'main', 'line' => 127]);
    $this->assertEquals(3, $data->getExecutionCount());
    $this->assertEquals('main', $data->getFunctionName());
    $this->assertEquals(127, $data->getLineNumber());
  }

  /**
   * Tests the `FunctionData::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $data = (new FunctionData())->jsonSerialize();
    $this->assertEquals(count(get_object_vars($data)), 3);
    $this->assertEquals(0, $data->count);
    $this->assertEquals('', $data->name);
    $this->assertEquals(0, $data->line);

    $data = (new FunctionData([
      'executionCount' => 3,
      'functionName' => 'main',
      'lineNumber' => 127
    ]))->jsonSerialize();

    $this->assertEquals(count(get_object_vars($data)), 3);
    $this->assertEquals(3, $data->count);
    $this->assertEquals('main', $data->name);
    $this->assertEquals(127, $data->line);
  }

  /**
   * Tests the `FunctionData::__toString()` method.
   */
  public function testToString() {
    $data = new FunctionData();
    $this->assertEquals('FNDA:0,', $data->toString(false));
    $this->assertEquals('FN:0,', $data->toString(true));

    $data = new FunctionData(['executionCount' => 3, 'functionName' => 'main', 'lineNumber' => 127]);
    $this->assertEquals('FNDA:3,main', $data->toString(false));
    $this->assertEquals('FN:127,main', $data->toString(true));
  }
}
