<?php namespace lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf};

/**
 * Tests the features of the {@see LineCoverage} class.
 */
#[TestDox('lcov\LineCoverage')]
final class LineCoverageTest extends TestCase {

	#[TestDox("::fromJson()")]
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$coverage = LineCoverage::fromJson(new \stdClass);
		assertThat($coverage->data, isEmpty());
		assertThat($coverage->found, equalTo(0));
		assertThat($coverage->hit, equalTo(0));

		// It should return an initialized instance for a non-empty map.
		$coverage = LineCoverage::fromJson((object) ["data" => [(object) ["lineNumber" => 127]], "found" => 23, "hit" => 11]);
		assertThat($coverage->data, countOf(1));
		assertThat($coverage->found, equalTo(23));
		assertThat($coverage->hit, equalTo(11));

		[$data] = $coverage->data;
		assertThat($data, isInstanceOf(LineData::class));
		assertThat($data->lineNumber, equalTo(127));
	}

	#[TestDox("->__toString()")]
	function testToString(): void {
		// It should return a format like 'LF:<found>\\nLH:<hit>'.
		assertThat((string) new LineCoverage, equalTo(str_replace("{eol}", PHP_EOL, "LF:0{eol}LH:0")));

		$data = new LineData(executionCount: 3, lineNumber: 127);
		assertThat((string) new LineCoverage(data: [$data], found: 23, hit: 11), equalTo(str_replace("{eol}", PHP_EOL, "$data{eol}LF:23{eol}LH:11")));
	}
}
