<?php declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/** Tests the features of the `Lcov\LineCoverage` class. */
class LineCoverageTest extends TestCase {

  /** @test LineCoverage::fromJson() */
  function testFromJson(): void {
    it('should return an instance with default values for an empty map', function() {
      $coverage = LineCoverage::fromJson(new \stdClass);
      expect($coverage->getData())->to->be->empty;
      expect($coverage->getFound())->to->equal(0);
      expect($coverage->getHit())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $coverage = LineCoverage::fromJson((object) ['data' => [(object) ['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);

      $entries = $coverage->getData();
      expect($entries)->to->have->lengthOf(1);
      expect($entries[0])->to->be->an->instanceOf(LineData::class);
      expect($entries[0]->getLineNumber())->to->equal(127);

      expect($coverage->getFound())->to->equal(23);
      expect($coverage->getHit())->to->equal(11);
    });
  }

  /** @test LineCoverage->jsonSerialize() */
  function testJsonSerialize(): void {
    it('should return a map with default values for a newly created instance', function() {
      $map = (new LineCoverage)->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->be->empty;
      expect($map->found)->to->equal(0);
      expect($map->hit)->to->equal(0);
    });

    it('should return a non-empty map for an initialized instance', function() {
      $map = (new LineCoverage(23, 11, [new LineData(0)]))->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->have->lengthOf(1);
      expect($map->data[0]->lineNumber)->to->equal(0);
      expect($map->found)->to->equal(23);
      expect($map->hit)->to->equal(11);
    });
  }

  /** @test LineCoverage->__toString() */
  function testToString(): void {
    it('should return a format like "LF:<found>\\n,LH:<hit>"', function() {
      expect((string) new LineCoverage)->to->equal(str_replace('{{eol}}', PHP_EOL, 'LF:0{{eol}}LH:0'));

      $data = new LineData(127, 3);
      $coverage = new LineCoverage(23, 11, [$data]);
      expect((string) $coverage)->to->equal(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}LF:23{{eol}}LH:11"));
    });
  }
}
