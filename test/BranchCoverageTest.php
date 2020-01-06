<?php declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/** @testdox Lcov\BranchCoverage */
class BranchCoverageTest extends TestCase {

  /** @testdox ::fromJson() */
  function testFromJson(): void {
    it('should return an instance with default values for an empty map', function() {
      $coverage = BranchCoverage::fromJson(new \stdClass);
      expect($coverage->getData())->to->be->empty;
      expect($coverage->getFound())->to->equal(0);
      expect($coverage->getHit())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $coverage = BranchCoverage::fromJson((object) ['data' => [(object) ['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
      expect($coverage->getFound())->to->equal(23);
      expect($coverage->getHit())->to->equal(11);

      $entries = $coverage->getData();
      expect($entries)->to->have->lengthOf(1);

      /** @var BranchData $entry */
      $entry = $entries[0];
      expect($entry)->to->be->an->instanceOf(BranchData::class);
      expect($entry->getLineNumber())->to->equal(127);
    });
  }

  /** @testdox ->jsonSerialize() */
  function testJsonSerialize(): void {
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
      expect($map->data[0]->lineNumber)->to->equal(0);
      expect($map->found)->to->equal(23);
      expect($map->hit)->to->equal(11);
    });
  }

  /** @testdox ->__toString() */
  function testToString(): void {
    it('should return a format like "BRF:<found>\\n,BRH:<hit>"', function() {
      expect((string) new BranchCoverage)->to->equal(str_replace('{{eol}}', PHP_EOL, 'BRF:0{{eol}}BRH:0'));

      $data = new BranchData(127, 3, 2);
      $coverage = new BranchCoverage(23, 11, [$data]);
      expect((string) $coverage)->to->equal(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}BRF:23{{eol}}BRH:11"));
    });
  }
}
