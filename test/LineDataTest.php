<?php
/**
 * Implementation of the `lcov\test\LineDataTest` class.
 */
namespace lcov\test;

use Codeception\{Specify};
use lcov\{LineData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\LineData
 */
class LineDataTest extends TestCase {
  use Specify;

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->specify('should return a null reference with a non-object value', function() {
      $this->assertNull(LineData::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $data = LineData::fromJSON([]);
      $this->assertInstanceOf(LineData::class, $data);
      $this->assertEmpty($data->getChecksum());
      $this->assertEquals(0, $data->getExecutionCount());
      $this->assertEquals(0, $data->getLineNumber());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $data = LineData::fromJSON(['checksum' => 'ed076287532e86365e841e92bfc50d8c', 'executionCount' => 3, 'lineNumber' => 127]);
      $this->assertInstanceOf(LineData::class, $data);
      $this->assertEquals('ed076287532e86365e841e92bfc50d8c', $data->getChecksum());
      $this->assertEquals(3, $data->getExecutionCount());
      $this->assertEquals(127, $data->getLineNumber());
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $data = (new LineData())->jsonSerialize();
      $this->assertCount(3, get_object_vars($data));
      $this->assertEmpty($data->checksum);
      $this->assertEquals(0, $data->executionCount);
      $this->assertEquals(0, $data->lineNumber);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $data = (new LineData(127, 3, 'ed076287532e86365e841e92bfc50d8c'))->jsonSerialize();
      $this->assertCount(3, get_object_vars($data));
      $this->assertEquals('ed076287532e86365e841e92bfc50d8c', $data->checksum);
      $this->assertEquals(3, $data->executionCount);
      $this->assertEquals(127, $data->lineNumber);
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->specify('should return a format like "DA:<lineNumber>,<executionCount>[,<checksum>]"', function() {
      $this->assertEquals('DA:0,0', (string) new LineData());
      $this->assertEquals('DA:127,3,ed076287532e86365e841e92bfc50d8c', (string) new LineData(127, 3, 'ed076287532e86365e841e92bfc50d8c'));
    });
  }
}
