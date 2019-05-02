<?php declare(strict_types=1);
namespace Lcov;

use PHPUnit\Framework\{TestCase};

/** Tests the features of the `Lcov\BranchCoverage` class. */
class BranchCoverageTest extends TestCase {

  /** @test BranchCoverage::fromJson() */
  function testFromJson(): void {
    // It should return an instance with default values for an empty map.
    $coverage = BranchCoverage::fromJson(new \stdClass);
    assertThat($coverage->getData(), isEmpty());
    assertThat($coverage->getFound(), equalTo(0));
    assertThat($coverage->getHit(), equalTo(0));

    // It should return an initialized instance for a non-empty map.
    $coverage = BranchCoverage::fromJson((object) ['data' => [(object) ['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);

    $entries = $coverage->getData();
    assertThat($entries, countOf(1));
    assertThat($entries[0], isInstanceOf(BranchData::class));
    assertThat($entries[0]->getLineNumber(), equalTo(127));

    assertThat($coverage->getFound(), equalTo(23));
    assertThat($coverage->getHit(), equalTo(11));
  }

  /** @test BranchCoverage->jsonSerialize() */
  function testJsonSerialize(): void {
    // It should return a map with default values for a newly created instance.
    $map = (new BranchCoverage)->jsonSerialize();
    assertThat(get_object_vars($map), countOf(3));
    assertThat($map->data, logicalAnd(isType('array'), isEmpty()));
    assertThat($map->found, equalTo(0));
    assertThat($map->hit, equalTo(0));

    // It should return a non-empty map for an initialized instance.
    $map = (new BranchCoverage(23, 11, [new BranchData(0, 0, 0)]))->jsonSerialize();
    assertThat(get_object_vars($map), countOf(3));
    assertThat($map->data, logicalAnd(isType('array'), countOf(1)));
    assertThat($map->data[0]->lineNumber, equalTo(0));
    assertThat($map->found, equalTo(23));
    assertThat($map->hit, equalTo(11));
  }

  /** @test BranchCoverage->__toString() */
  function testToString(): void {
    // It should return a format like "BRF:<found>\\n,BRH:<hit>".
    assertThat((string) new BranchCoverage, equalTo(str_replace('{{eol}}', PHP_EOL, 'BRF:0{{eol}}BRH:0')));

    $data = new BranchData(127, 3, 2);
    $coverage = new BranchCoverage(23, 11, [$data]);
    assertThat((string) $coverage, equalTo(str_replace('{{eol}}', PHP_EOL, "$data{{eol}}BRF:23{{eol}}BRH:11")));
  }
}
