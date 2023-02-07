<?php namespace lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use function phpunit\expect\{expect, it};

/**
 * Tests the features of the {@see FunctionData} class.
 */
#[TestDox('lcov\FunctionData')]
class FunctionDataTest extends TestCase {

	#[TestDox("::fromJson()")]
	function testFromJson(): void {
		it("should return an instance with default values for an empty map", function() {
			$data = FunctionData::fromJson(new \stdClass);
			expect($data->executionCount)->to->equal(0);
			expect($data->functionName)->to->be->empty;
			expect($data->lineNumber)->to->equal(0);
		});

		it("should return an initialized instance for a non-empty map", function() {
			$data = FunctionData::fromJson((object) ["executionCount" => 3, "functionName" => "main", "lineNumber" => 127]);
			expect($data->executionCount)->to->equal(3);
			expect($data->functionName)->to->equal("main");
			expect($data->lineNumber)->to->equal(127);
		});
	}

	#[TestDox("->toString()")]
	function testToString(): void {
		it("should return a format like 'FN:<lineNumber>,<functionName>' when used as definition", function() {
			expect((new FunctionData)->toString(true))->to->equal("FN:0,");
			expect((new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127))->toString(true))->to->equal("FN:127,main");
		});

		it("should return a format like 'FNDA:<executionCount>,<functionName>' when used as data", function() {
			expect((new FunctionData)->toString(false))->to->equal("FNDA:0,");
			expect((new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127))->toString(false))->to->equal("FNDA:3,main");
		});
	}
}
