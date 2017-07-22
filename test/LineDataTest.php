<?php
declare(strict_types=1);
namespace lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `lcov\LineData` class.
 */
class LineDataTest extends TestCase {

  /**
   * @test LineData::fromJSON
   */
  public function testFromJSON() {
    it('should return a null reference with a non-object value', function() {
      expect(LineData::fromJSON('foo'))->to->be->null;
    });

    it('should return an instance with default values for an empty map', function() {
      $data = LineData::fromJSON([]);
      expect($data)->to->be->instanceOf(LineData::class);
      expect($data->getChecksum())->to->be->empty;
      expect($data->getExecutionCount())->to->equal(0);
      expect($data->getLineNumber())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $data = LineData::fromJSON(['checksum' => 'ed076287532e86365e841e92bfc50d8c', 'executionCount' => 3, 'lineNumber' => 127]);
      expect($data)->to->be->instanceOf(LineData::class);
      expect($data->getChecksum())->to->equal('ed076287532e86365e841e92bfc50d8c');
      expect($data->getExecutionCount())->to->equal(3);
      expect($data->getLineNumber())->to->equal(127);
    });
  }

  /**
   * @test LineData::jsonSerialize
   */
  public function testJsonSerialize() {
    it('should return a map with default values for a newly created instance', function() {
      $data = (new LineData)->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(3);
      expect($data->checksum)->to->be->empty;
      expect($data->executionCount)->to->equal(0);
      expect($data->lineNumber)->to->equal(0);
    });

    it('should return a non-empty map for an initialized instance', function() {
      $data = (new LineData(127, 3, 'ed076287532e86365e841e92bfc50d8c'))->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(3);
      expect($data->checksum)->to->equal('ed076287532e86365e841e92bfc50d8c');
      expect($data->executionCount)->to->equal(3);
      expect($data->lineNumber)->to->equal(127);
    });
  }

  /**
   * @test LineData::__toString
   */
  public function testToString() {
    it('should return a format like "DA:<lineNumber>,<executionCount>[,<checksum>]"', function() {
      expect((string) new LineData)->to->equal('DA:0,0');
      expect((string) new LineData(127, 3, 'ed076287532e86365e841e92bfc50d8c'))->to->equal('DA:127,3,ed076287532e86365e841e92bfc50d8c');
    });
  }
}
