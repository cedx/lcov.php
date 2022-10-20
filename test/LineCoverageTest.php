<?php namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Expect\{expect, it};

/**
 * @testdox Lcov\LineCoverage
 */
class LineCoverageTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		it("should return an instance with default values for an empty map", function() {
			$coverage = LineCoverage::fromJson(new \stdClass);
			expect($coverage->data)->to->be->empty;
			expect($coverage->found)->to->equal(0);
			expect($coverage->hit)->to->equal(0);
		});

		it("should return an initialized instance for a non-empty map", function() {
			$coverage = LineCoverage::fromJson((object) ["data" => [(object) ["lineNumber" => 127]], "found" => 23, "hit" => 11]);
			expect($coverage->data)->to->have->lengthOf(1);
			expect($coverage->found)->to->equal(23);
			expect($coverage->hit)->to->equal(11);

			[$data] = $coverage->data;
			expect($data)->to->be->an->instanceOf(LineData::class);
			expect($data->lineNumber)->to->equal(127);
		});
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		it("should return a format like 'LF:<found>\\nLH:<hit>'", function() {
			expect((string) new LineCoverage)->to->equal(str_replace("{eol}", PHP_EOL, "LF:0{eol}LH:0"));

			$data = new LineData(executionCount: 3, lineNumber: 127);
			expect((string) new LineCoverage(data: [$data], found: 23, hit: 11))->to->equal(str_replace("{eol}", PHP_EOL, "$data{eol}LF:23{eol}LH:11"));
		});
	}
}
