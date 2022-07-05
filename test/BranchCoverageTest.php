<?php namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf, isType, logicalAnd};

/**
 * @testdox Lcov\BranchCoverage
 */
class BranchCoverageTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$coverage = BranchCoverage::fromJson(new \stdClass);
		assertThat($coverage->data, isEmpty());
		assertThat($coverage->found, equalTo(0));
		assertThat($coverage->hit, equalTo(0));

		// It should return an initialized instance for a non-empty map.
		$coverage = BranchCoverage::fromJson((object) ["data" => [(object) ["lineNumber" => 127]], "found" => 23, "hit" => 11]);
		assertThat($coverage->data, countOf(1));
		assertThat($coverage->found, equalTo(23));
		assertThat($coverage->hit, equalTo(11));

		[$data] = $coverage->data;
		assertThat($data, isInstanceOf(BranchData::class));
		assertThat($data->lineNumber, equalTo(127));
	}

	/**
	 * @testdox ->jsonSerialize()
	 */
	function testJsonSerialize(): void {
		// It should return a map with default values for a newly created instance.
		$map = (new BranchCoverage)->jsonSerialize();
		assertThat(get_object_vars($map), countOf(3));
		assertThat($map->data, logicalAnd(isType("array"), isEmpty()));
		assertThat($map->found, equalTo(0));
		assertThat($map->hit, equalTo(0));

		// It should return a non-empty map for an initialized instance.
		$map = (new BranchCoverage(found: 23, hit: 11, data: [new BranchData]))->jsonSerialize();
		assertThat(get_object_vars($map), countOf(3));
		assertThat($map->data, logicalAnd(isType("array"), countOf(1)));

		[$data] = $map->data;
		assertThat($data, isType("object"));
		assertThat($data->lineNumber, equalTo(0));
		assertThat($map->found, equalTo(23));
		assertThat($map->hit, equalTo(11));
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		// It should return a format like "BRF:<found>\\nBRH:<hit>".
		assertThat((string) new BranchCoverage, equalTo(str_replace("{eol}", PHP_EOL, "BRF:0{eol}BRH:0")));

		$data = new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1);
		$coverage = new BranchCoverage(data: [$data], found: 23, hit: 11);
		assertThat((string) $coverage, equalTo(str_replace("{eol}", PHP_EOL, "$data{eol}BRF:23{eol}BRH:11")));
	}
}
