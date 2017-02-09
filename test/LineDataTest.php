<?php
/**
 * Implementation of the `lcov\test\LineDataTest` class.
 */
namespace lcov\test;

use lcov\{LineData};
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \lcov\LineData` class.
 */
class LineDataTest extends TestCase {

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->assertNull(LineData::fromJSON('foo'));

    $data = LineData::fromJSON([]);
    $this->assertInstanceOf(LineData::class, $data);
    $this->assertEmpty($data->getChecksum());
    $this->assertEquals(0, $data->getExecutionCount());
    $this->assertEquals(0, $data->getLineNumber());

    $data = LineData::fromJSON(['checksum' => 'ed076287532e86365e841e92bfc50d8c', 'executionCount' => 3, 'lineNumber' => 127]);
    $this->assertInstanceOf(LineData::class, $data);
    $this->assertEquals('ed076287532e86365e841e92bfc50d8c', $data->getChecksum());
    $this->assertEquals(3, $data->getExecutionCount());
    $this->assertEquals(127, $data->getLineNumber());
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $data = (new LineData())->jsonSerialize();
    $this->assertCount(3, get_object_vars($data));
    $this->assertEmpty($data->checksum);
    $this->assertEquals(0, $data->executionCount);
    $this->assertEquals(0, $data->lineNumber);

    $data = (new LineData(127, 3, 'ed076287532e86365e841e92bfc50d8c'))->jsonSerialize();
    $this->assertCount(3, get_object_vars($data));
    $this->assertEquals('ed076287532e86365e841e92bfc50d8c', $data->checksum);
    $this->assertEquals(3, $data->executionCount);
    $this->assertEquals(127, $data->lineNumber);
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->assertEquals('DA:0,0', (string) new LineData());
    $this->assertEquals('DA:127,3,ed076287532e86365e841e92bfc50d8c', (string) new LineData(127, 3, 'ed076287532e86365e841e92bfc50d8c'));
  }
}
