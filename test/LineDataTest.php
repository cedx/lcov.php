<?php namespace lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use function PHPUnit\Framework\{assertThat, equalTo, isEmpty};

/**
 * Tests the features of the {@see LineData} class.
 */
#[TestDox('lcov\LineData')]
final class LineDataTest extends TestCase {

	#[TestDox("::fromJson()")]
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$data = LineData::fromJson(new \stdClass);
		assertThat($data->checksum, isEmpty());
		assertThat($data->executionCount, equalTo(0));
		assertThat($data->lineNumber, equalTo(0));

		// It should return an initialized instance for a non-empty map.
		$data = LineData::fromJson((object) ["checksum" => "ed076287532e86365e841e92bfc50d8c", "executionCount" => 3, "lineNumber" => 127]);
		assertThat($data->checksum, equalTo("ed076287532e86365e841e92bfc50d8c"));
		assertThat($data->executionCount, equalTo(3));
		assertThat($data->lineNumber, equalTo(127));
	}

	#[TestDox("->__toString()")]
	function testToString(): void {
		// It should return a format like 'DA:<lineNumber>,<executionCount>[,<checksum>]'.
		assertThat((string) new LineData, equalTo("DA:0,0"));

		$data = new LineData(checksum: "ed076287532e86365e841e92bfc50d8c", executionCount: 3, lineNumber: 127);
		assertThat((string) $data, equalTo("DA:127,3,ed076287532e86365e841e92bfc50d8c"));
	}
}
