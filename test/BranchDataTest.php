<?php namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Expect\{expect, it};

/**
 * @testdox Lcov\BranchData
 */
class BranchDataTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		it("should return an instance with default values for an empty map", function() {
			$data = BranchData::fromJson(new \stdClass);
			expect($data->blockNumber)->to->equal(0);
			expect($data->branchNumber)->to->equal(0);
			expect($data->lineNumber)->to->equal(0);
			expect($data->taken)->to->equal(0);
		});

		it("should return an initialized instance for a non-empty map", function() {
			$data = BranchData::fromJson((object) ["blockNumber" => 3, "branchNumber" => 2, "lineNumber" => 127, "taken" => 1]);
			expect($data->blockNumber)->to->equal(3);
			expect($data->branchNumber)->to->equal(2);
			expect($data->lineNumber)->to->equal(127);
			expect($data->taken)->to->equal(1);
		});
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		it("should return a format like 'BRDA:<lineNumber>,<blockNumber>,<branchNumber>,<taken>'", function() {
			expect((string) new BranchData)->to->equal("BRDA:0,0,0,-");
			expect((string) new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1))->to->equal("BRDA:127,3,2,1");
		});
	}
}
