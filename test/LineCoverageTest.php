<?php
declare(strict_types=1);
namespace lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `lcov\LineCoverage` class.
 */
class LineCoverageTest extends TestCase {

  /**
   * @test LineCoverage::fromJSON
   */
  public function testFromJSON() {
    it('should return a null reference with a non-object value', function() {
      expect(LineCoverage::fromJSON('foo'))->to->be->null;
    });

    it('should return an instance with default values for an empty map', function() {
      $coverage = LineCoverage::fromJSON([]);
      expect($coverage)->to->be->instanceOf(LineCoverage::class);
      expect($coverage->getData())->to->be->empty;
      expect($coverage->getFound())->to->equal(0);
      expect($coverage->getHit())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $coverage = LineCoverage::fromJSON(['data' => [['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
      expect($coverage)->to->be->instanceOf(LineCoverage::class);

      $entries = $coverage->getData();
      expect($entries)->to->have->lengthOf(1);
      expect($entries[0])->to->be->instanceOf(LineData::class);
      expect($entries[0]->getLineNumber())->to->equal(127);

      expect($coverage->getFound())->to->equal(23);
      expect($coverage->getHit())->to->equal(11);
    });
  }

  /**
   * @test LineCoverage::jsonSerialize
   */
  public function testJsonSerialize() {
    it('should return a map with default values for a newly created instance', function() {
      $map = (new LineCoverage)->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->be->an('array')->and->be->empty;
      expect($map->found)->to->equal(0);
      expect($map->hit)->to->equal(0);
    });

    it('should return a non-empty map for an initialized instance', function() {
      $map = (new LineCoverage(23, 11, [new LineData]))->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->be->an('array')->and->have->lengthOf(1);
      expect($map->data[0])->to->be->an('object')->and->have->property('lineNumber')->that->is->an('int');
      expect($map->found)->to->equal(23);
      expect($map->hit)->to->equal(11);
    });
  }

  /**
   * @test LineCoverage::__toString
   */
  public function testToString() {
    it('should return a format like "LF:<found>\\n,LH:<hit>"', function() {
      expect((string) new LineCoverage)->to->equal(str_replace('{{eol}}', PHP_EOL, 'LF:0{{eol}}LH:0'));

      $data = new LineData(127, 3);
      $coverage = new LineCoverage(23, 11, [$data]);
      expect((string) $coverage)->to->equal(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}LF:23{{eol}}LH:11"));
    });
  }
}
