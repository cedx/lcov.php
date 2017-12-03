<?php
declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `Lcov\FunctionCoverage` class.
 */
class FunctionCoverageTest extends TestCase {

  /**
   * @test FunctionCoverage::fromJson
   */
  public function testFromJson(): void {
    it('should return a null reference with a non-object value', function() {
      expect(FunctionCoverage::fromJson('foo'))->to->be->null;
    });

    it('should return an instance with default values for an empty map', function() {
      $coverage = FunctionCoverage::fromJson([]);
      expect($coverage)->to->be->instanceOf(FunctionCoverage::class);
      expect($coverage->getData())->to->be->empty;
      expect($coverage->getFound())->to->equal(0);
      expect($coverage->getHit())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $coverage = FunctionCoverage::fromJson(['data' => [['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
      expect($coverage)->to->be->instanceOf(FunctionCoverage::class);

      $entries = $coverage->getData();
      expect($entries)->to->have->lengthOf(1);
      expect($entries[0])->to->be->instanceOf(FunctionData::class);
      expect($entries[0]->getLineNumber())->to->equal(127);

      expect($coverage->getFound())->to->equal(23);
      expect($coverage->getHit())->to->equal(11);
    });
  }

  /**
   * @test FunctionCoverage::jsonSerialize
   */
  public function testJsonSerialize(): void {
    it('should return a map with default values for a newly created instance', function() {
      $map = (new FunctionCoverage)->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->be->an('array')->and->be->empty;
      expect($map->found)->to->equal(0);
      expect($map->hit)->to->equal(0);
    });

    it('should return a non-empty map for an initialized instance', function() {
      $map = (new FunctionCoverage(23, 11, [new FunctionData('', 0)]))->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->an('array')->and->have->lengthOf(1);
      expect($map->data[0])->to->be->an('object')->and->have->property('lineNumber')->that->is->an('int');
      expect($map->found)->to->equal(23);
      expect($map->hit)->to->equal(11);
    });
  }

  /**
   * @test FunctionCoverage::__toString
   */
  public function testToString(): void {
    it('should return a format like "FNF:<found>\\n,FNH:<hit>"', function() {
      expect((string) new FunctionCoverage)->to->equal(str_replace('{{eol}}', PHP_EOL, 'FNF:0{{eol}}FNH:0'));

      $coverage = new FunctionCoverage(23, 11, [new FunctionData('main', 127, 3)]);
      expect((string) $coverage)->to->equal(str_replace('{{eol}}', PHP_EOL, 'FN:127,main{{eol}}FNDA:3,main{{eol}}FNF:23{{eol}}FNH:11'));
    });
  }
}
