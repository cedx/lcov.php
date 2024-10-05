<?php declare(strict_types=1);
namespace lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf};

/**
 * Tests the features of the {@see LineCoverage} class.
 */
#[TestDox("LineCoverage")]
final class LineCoverageTest extends TestCase {

	#[Test, TestDox("fromJson()")]
	function fromJson(): void {
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

	#[Test, TestDox("__toString()")]
	function testToString(): void {
		// It should return a format like 'LF:<found>\\nLH:<hit>'.
		assertThat((string) new LineCoverage, equalTo(strtr("LF:0{eol}LH:0", ["{eol}" => PHP_EOL])));

		$data = new LineData(executionCount: 3, lineNumber: 127);
		$coverage = new LineCoverage(data: [$data], found: 23, hit: 11);
		assertThat((string) $coverage, equalTo(strtr("$data{eol}LF:23{eol}LH:11", ["{eol}" => PHP_EOL])));
	}
}
