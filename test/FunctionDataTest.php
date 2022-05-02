<?php declare(strict_types=1);
namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty};

/**
 * @testdox Lcov\FunctionData
 */
class FunctionDataTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$data = FunctionData::fromJson(new \stdClass);
		assertThat($data->executionCount, equalTo(0));
		assertThat($data->functionName, isEmpty());
		assertThat($data->lineNumber, equalTo(0));

		// It should return an initialized instance for a non-empty map.
		$data = FunctionData::fromJson((object) ["executionCount" => 3, "functionName" => "main", "lineNumber" => 127]);
		assertThat($data->executionCount, equalTo(3));
		assertThat($data->functionName, equalTo("main"));
		assertThat($data->lineNumber, equalTo(127));
	}

	/**
	 * @testdox ->jsonSerialize()
	 */
	function testJsonSerialize(): void {
		// It should return a map with default values for a newly created instance.
		$data = (new FunctionData("", 0))->jsonSerialize();
		assertThat(get_object_vars($data), countOf(3));
		assertThat($data->executionCount, equalTo(0));
		assertThat($data->functionName, isEmpty());
		assertThat($data->lineNumber, equalTo(0));

		// It should return a non-empty map for an initialized instance.
		$data = (new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127))->jsonSerialize();
		assertThat(get_object_vars($data), countOf(3));
		assertThat($data->executionCount, equalTo(3));
		assertThat($data->functionName, equalTo("main"));
		assertThat($data->lineNumber, equalTo(127));
	}

	/**
	 * @testdox ->toString()
	 */
	function testToString(): void {
		// It should return a format like "FN:<lineNumber>,<functionName>" when used as definition.
		assertThat((new FunctionData)->toString(true), equalTo("FN:0,"));
		assertThat((new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127))->toString(true), equalTo("FN:127,main"));

		// It should return a format like "FNDA:<executionCount>,<functionName>" when used as data.
		assertThat((new FunctionData)->toString(false), equalTo("FNDA:0,"));
		assertThat((new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127))->toString(false), equalTo("FNDA:3,main"));
	}
}
