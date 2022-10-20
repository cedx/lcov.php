<?php namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Expect\{expect, it};

/**
 * @testdox Lcov\BranchCoverage
 */
class BranchCoverageTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		it("should return an instance with default values for an empty map", function() {
			$coverage = BranchCoverage::fromJson(new \stdClass);
			expect($coverage->data)->to->be->empty;
			expect($coverage->found)->to->equal(0);
			expect($coverage->hit)->to->equal(0);
		});

		it("should return an initialized instance for a non-empty map", function() {
			$coverage = BranchCoverage::fromJson((object) ["data" => [(object) ["lineNumber" => 127]], "found" => 23, "hit" => 11]);
			expect($coverage->data)->to->have->lengthOf(1);
			expect($coverage->found)->to->equal(23);
			expect($coverage->hit)->to->equal(11);

			[$data] = $coverage->data;
			expect($data)->to->be->an->instanceOf(BranchData::class);
			expect($data->lineNumber)->to->equal(127);
		});
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		it("should return a format like 'BRF:<found>\\nBRH:<hit>'", function() {
			expect((string) new BranchCoverage)->to->equal(str_replace("{eol}", PHP_EOL, "BRF:0{eol}BRH:0"));

			$data = new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1);
			$coverage = new BranchCoverage(data: [$data], found: 23, hit: 11);
			expect((string) $coverage)->to->equal(str_replace("{eol}", PHP_EOL, "$data{eol}BRF:23{eol}BRH:11"));
		});
	}
}
