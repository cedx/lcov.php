<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf};

/**
 * Tests the features of the {@see FunctionCoverage} class.
 */
#[TestDox("FunctionCoverage")]
final class FunctionCoverageTest extends TestCase {

	#[Test, TestDox("fromJson()")]
	function fromJson(): void {
		// It should return an instance with default values for an empty map.
		$coverage = FunctionCoverage::fromJson(new \stdClass);
		assertThat($coverage->data, isEmpty());
		assertThat($coverage->found, equalTo(0));
		assertThat($coverage->hit, equalTo(0));

		// It should return an initialized instance for a non-empty map.
		$coverage = FunctionCoverage::fromJson((object) ["data" => [(object) ["lineNumber" => 127]], "found" => 23, "hit" => 11]);
		assertThat($coverage->data, countOf(1));
		assertThat($coverage->found, equalTo(23));
		assertThat($coverage->hit, equalTo(11));

		[$data] = $coverage->data;
		assertThat($data, isInstanceOf(FunctionData::class));
		assertThat($data->lineNumber, equalTo(127));
	}

	#[Test, TestDox("__toString()")]
	function testToString(): void {
		// It should return a format like 'FNF:<found>\\nFNH:<hit>'.
		assertThat((string) new FunctionCoverage, equalTo(strtr("FNF:0{eol}FNH:0", ["{eol}" => PHP_EOL])));

		$data = new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127);
		$coverage = new FunctionCoverage(data: [$data], found: 23, hit: 11);
		assertThat((string) $coverage, equalTo(strtr("FN:127,main{eol}FNDA:3,main{eol}FNF:23{eol}FNH:11", ["{eol}" => PHP_EOL])));
	}
}
