<?php namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Expect\{expect, it};

/**
 * @testdox Lcov\LineData
 */
class LineDataTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		it("should return an instance with default values for an empty map", function() {
			$data = LineData::fromJson(new \stdClass);
			expect($data->checksum)->to->be->empty;
			expect($data->executionCount)->to->equal(0);
			expect($data->lineNumber)->to->equal(0);
		});

		it("should return an initialized instance for a non-empty map", function() {
			$data = LineData::fromJson((object) ["checksum" => "ed076287532e86365e841e92bfc50d8c", "executionCount" => 3, "lineNumber" => 127]);
			expect($data->checksum)->to->equal("ed076287532e86365e841e92bfc50d8c");
			expect($data->executionCount)->to->equal(3);
			expect($data->lineNumber)->to->equal(127);
		});
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		it("should return a format like 'DA:<lineNumber>,<executionCount>[,<checksum>]'", function() {
			expect((string) new LineData)->to->equal("DA:0,0");

			$data = new LineData(checksum: "ed076287532e86365e841e92bfc50d8c", executionCount: 3, lineNumber: 127);
			expect((string) $data)->to->equal("DA:127,3,ed076287532e86365e841e92bfc50d8c");
		});
	}
}
