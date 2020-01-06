<?php declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/** @testdox Lcov\FunctionCoverage */
class FunctionCoverageTest extends TestCase {

  /** @testdox ::fromJson() */
  function testFromJson(): void {
    it('should return an instance with default values for an empty map', function() {
      $coverage = FunctionCoverage::fromJson(new \stdClass);
      expect($coverage->getData())->to->be->empty;
      expect($coverage->getFound())->to->equal(0);
      expect($coverage->getHit())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $coverage = FunctionCoverage::fromJson((object) ['data' => [(object) ['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
      expect($coverage->getFound())->to->equal(23);
      expect($coverage->getHit())->to->equal(11);

      $entries = $coverage->getData();
      expect($entries)->to->have->lengthOf(1);

      /** @var FunctionData $entry */
      $entry = $entries[0];
      expect($entry)->to->be->an->instanceOf(FunctionData::class);
      expect($entry->getLineNumber())->to->equal(127);
    });
  }

  /** @testdox ->jsonSerialize() */
  function testJsonSerialize(): void {
    it('should return a map with default values for a newly created instance', function() {
      $map = (new FunctionCoverage)->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->be->empty;
      expect($map->found)->to->equal(0);
      expect($map->hit)->to->equal(0);
    });

    it('should return a non-empty map for an initialized instance', function() {
      $map = (new FunctionCoverage(23, 11, [new FunctionData('', 0)]))->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(3);
      expect($map->data)->to->be->an('array')->and->have->lengthOf(1);
      expect($map->data[0]->lineNumber)->to->equal(0);
      expect($map->found)->to->equal(23);
      expect($map->hit)->to->equal(11);
    });
  }

  /** @testdox ->__toString() */
  function testToString(): void {
    it('should return a format like "FNF:<found>\\n,FNH:<hit>"', function() {
      expect((string) new FunctionCoverage)->to->equal(str_replace('{{eol}}', PHP_EOL, 'FNF:0{{eol}}FNH:0'));

      $coverage = new FunctionCoverage(23, 11, [new FunctionData('main', 127, 3)]);
      expect((string) $coverage)->to->equal(str_replace('{{eol}}', PHP_EOL, 'FN:127,main{{eol}}FNDA:3,main{{eol}}FNF:23{{eol}}FNH:11'));
    });
  }
}
