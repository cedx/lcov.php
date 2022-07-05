<?php namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Framework\{assertThat, countOf, equalTo};

/**
 * @testdox Lcov\BranchData
 */
class BranchDataTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$data = BranchData::fromJson(new \stdClass);
		assertThat($data->blockNumber, equalTo(0));
		assertThat($data->branchNumber, equalTo(0));
		assertThat($data->lineNumber, equalTo(0));
		assertThat($data->taken, equalTo(0));

		// It should return an initialized instance for a non-empty map.
		$data = BranchData::fromJson((object) ["blockNumber" => 3, "branchNumber" => 2, "lineNumber" => 127, "taken" => 1]);
		assertThat($data->blockNumber, equalTo(3));
		assertThat($data->branchNumber, equalTo(2));
		assertThat($data->lineNumber, equalTo(127));
		assertThat($data->taken, equalTo(1));
	}

	/**
	 * @testdox ->jsonSerialize()
	 */
	function testJsonSerialize(): void {
		// It should return a map with default values for a newly created instance.
		$map = (new BranchData)->jsonSerialize();
		assertThat(get_object_vars($map), countOf(4));
		assertThat($map->blockNumber, equalTo(0));
		assertThat($map->branchNumber, equalTo(0));
		assertThat($map->lineNumber, equalTo(0));
		assertThat($map->taken, equalTo(0));

		// It should return a non-empty map for an initialized instance.
		$map = (new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1))->jsonSerialize();
		assertThat(get_object_vars($map), countOf(4));
		assertThat($map->blockNumber, equalTo(3));
		assertThat($map->branchNumber, equalTo(2));
		assertThat($map->lineNumber, equalTo(127));
		assertThat($map->taken, equalTo(1));
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		// It should return a format like "BRDA:<lineNumber>,<blockNumber>,<branchNumber>,<taken>".
		assertThat((string) new BranchData, equalTo("BRDA:0,0,0,-"));
		assertThat((string) new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1), equalTo("BRDA:127,3,2,1"));
	}
}
