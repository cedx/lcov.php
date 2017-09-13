<?php
declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `Lcov\BranchCoverage` class.
 */
class BranchCoverageTest extends TestCase {

  /**
   * @test BranchCoverage::fromJson
   */
  public function testFromJson() {
    it('should return a null reference with a non-object value', function() {
      expect(BranchCoverage::fromJson('foo'))->to->be->null;
    });

    it('should return an instance with default values for an empty map', function() {
      $coverage = BranchCoverage::fromJson([]);
      expect($coverage)->to->be->instanceOf(BranchCoverage::class);
      expect($coverage->getData())->to->be->empty;
      expect($coverage->getFound())->to->equal(0);
      expect($coverage->getHit())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $coverage = BranchCoverage::fromJson(['data' => [['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
      expect($coverage)->to->be->instanceOf(BranchCoverage::class);

      $entries = $coverage->getData();
      expect($entries)->to->have->lengthOf(1);
      expect($entries[0])->to->be->instanceOf(BranchData::class);
      expect($entries[0]->getLineNumber())->to->equal(127);

      expect($coverage->getFound())->to->equal(23);
      expect($coverage->getHit())->to->equal(11);
    });
  }

  /**
   * @test BranchCoverage::jsonSerialize
   */
  public function testJsonSerialize() {
    it('should return a map with default values for a newly created instance', function() {
      $map = (new BranchCoverage)->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->be->an('array')->and->be->empty;
      expect($map->found)->to->equal(0);
      expect($map->hit)->to->equal(0);
    });

    it('should return a non-empty map for an initialized instance', function() {
      $map = (new BranchCoverage(23, 11, [new BranchData(0, 0, 0)]))->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->be->an('array')->and->have->lengthOf(1);
      expect($map->data[0])->to->be->an('object')->and->have->property('lineNumber')->that->is->an('int');

      expect($map->found)->to->equal(23);
      expect($map->hit)->to->equal(11);
    });
  }

  /**
   * @test BranchCoverage::__toString
   */
  public function testToString() {
    it('should return a format like "BRF:<found>\\n,BRH:<hit>"', function() {
      expect((string) new BranchCoverage)->to->equal(str_replace('{{eol}}', PHP_EOL, 'BRF:0{{eol}}BRH:0'));

      $data = new BranchData(127, 3, 2);
      $coverage = new BranchCoverage(23, 11, [$data]);
      expect((string) $coverage)->to->equal(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}BRF:23{{eol}}BRH:11"));
    });
  }
}
