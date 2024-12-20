<?php declare(strict_types=1);
namespace lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertThat, equalTo};

/**
 * Tests the features of the {@see BranchData} class.
 */
#[TestDox("BranchData")]
final class BranchDataTest extends TestCase {

	#[Test, TestDox("fromJson()")]
	function fromJson(): void {
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

	#[Test, TestDox("__toString()")]
	function testToString(): void {
		// It should return a format like 'BRDA:<lineNumber>,<blockNumber>,<branchNumber>,<taken>'.
		assertThat((string) new BranchData, equalTo("BRDA:0,0,0,-"));
		assertThat((string) new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1), equalTo("BRDA:127,3,2,1"));
	}
}
