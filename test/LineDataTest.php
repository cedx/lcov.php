<?php
/**
 * Implementation of the `lcov\test\LineDataTest` class.
 */
namespace lcov\test;
use lcov\{LineData};

/**
 * Tests the features of the `lcov\LineData` class.
 */
class LineDataTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `LineData` constructor.
   */
  public function testConstructor() {
    $data = new LineData([
      'checksum' => 'ed076287532e86365e841e92bfc50d8c',
      'executionCount' => 3,
      'lineNumber' => 127
    ]);

    $this->assertEquals('ed076287532e86365e841e92bfc50d8c', $data->getChecksum());
    $this->assertEquals(3, $data->getExecutionCount());
    $this->assertEquals(127, $data->getLineNumber());
  }

  /**
   * Tests the `LineData::fromJSON()` method.
   */
  public function testFromJSON() {
    $this->assertNull(LineData::fromJSON('foo'));

    $data = LineData::fromJSON([]);
    $this->assertEquals('', $data->getChecksum());
    $this->assertEquals(0, $data->getExecutionCount());
    $this->assertEquals(0, $data->getLineNumber());

    $data = LineData::fromJSON(['checksum' => 'ed076287532e86365e841e92bfc50d8c', 'count' => 3, 'line' => 127]);
    $this->assertEquals('ed076287532e86365e841e92bfc50d8c', $data->getChecksum());
    $this->assertEquals(3, $data->getExecutionCount());
    $this->assertEquals(127, $data->getLineNumber());
  }

  /**
   * Tests the `LineData::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $data = (new LineData())->jsonSerialize();
    $this->assertCount(3, get_object_vars($data));
    $this->assertEquals('', $data->checksum);
    $this->assertEquals(0, $data->count);
    $this->assertEquals(0, $data->line);

    $data = (new LineData([
      'checksum' => 'ed076287532e86365e841e92bfc50d8c',
      'executionCount' => 3,
      'lineNumber' => 127
    ]))->jsonSerialize();

    $this->assertCount(3, get_object_vars($data));
    $this->assertEquals('ed076287532e86365e841e92bfc50d8c', $data->checksum);
    $this->assertEquals(3, $data->count);
    $this->assertEquals(127, $data->line);
  }

  /**
   * Tests the `LineData::__toString()` method.
   */
  public function testToString() {
    $data = new LineData();
    $this->assertEquals('DA:0,0', (string) $data);

    $data = new LineData(['checksum' => 'ed076287532e86365e841e92bfc50d8c', 'executionCount' => 3, 'lineNumber' => 127]);
    $this->assertEquals('DA:127,3,ed076287532e86365e841e92bfc50d8c', (string) $data);
  }
}
