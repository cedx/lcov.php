<?php namespace lcov;

use PHPUnit\Framework\{TestCase};
use function phpunit\expect\{expect, it};

/**
 * @testdox lcov\FunctionCoverage
 */
class FunctionCoverageTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		it("should return an instance with default values for an empty map", function() {
			$coverage = FunctionCoverage::fromJson(new \stdClass);
			expect($coverage->data)->to->be->empty;
			expect($coverage->found)->to->equal(0);
			expect($coverage->hit)->to->equal(0);
		});

		it("should return an initialized instance for a non-empty map", function() {
			$coverage = FunctionCoverage::fromJson((object) ["data" => [(object) ["lineNumber" => 127]], "found" => 23, "hit" => 11]);
			expect($coverage->data)->to->have->lengthOf(1);
			expect($coverage->found)->to->equal(23);
			expect($coverage->hit)->to->equal(11);

			[$data] = $coverage->data;
			expect($data)->to->be->an->instanceOf(FunctionData::class);
			expect($data->lineNumber)->to->equal(127);
		});
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		it("should return a format like 'FNF:<found>\\nFNH:<hit>'", function() {
			expect((string) new FunctionCoverage)->to->equal(str_replace("{eol}", PHP_EOL, "FNF:0{eol}FNH:0"));

			$coverage = new FunctionCoverage(data: [new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127)], found: 23, hit: 11);
			expect((string) $coverage)->to->equal(str_replace("{eol}", PHP_EOL, "FN:127,main{eol}FNDA:3,main{eol}FNF:23{eol}FNH:11"));
		});
	}
}
