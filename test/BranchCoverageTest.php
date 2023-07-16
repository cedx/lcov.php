<?php namespace lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf};

/**
 * Tests the features of the {@see BranchCoverage} class.
 */
#[TestDox('lcov\BranchCoverage')]
final class BranchCoverageTest extends TestCase {

	#[TestDox("::fromJson()")]
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$coverage = BranchCoverage::fromJson(new \stdClass);
		assertThat($coverage->data, isEmpty());
		assertThat($coverage->found, equalTo(0));
		assertThat($coverage->hit, equalTo(0));

		// It should return an initialized instance for a non-empty map.
		$coverage = BranchCoverage::fromJson((object) ["data" => [(object) ["lineNumber" => 127]], "found" => 23, "hit" => 11]);
		assertThat($coverage->data, countOf(1));
		assertThat($coverage->found, equalTo(23));
		assertThat($coverage->hit, equalTo(11));

		[$data] = $coverage->data;
		assertThat($data, isInstanceOf(BranchData::class));
		assertThat($data->lineNumber, equalTo(127));
	}

	#[TestDox("->__toString()")]
	function testToString(): void {
		// It should return a format like 'BRF:<found>\\nBRH:<hit>'.
		assertThat((string) new BranchCoverage, equalTo(str_replace("{eol}", PHP_EOL, "BRF:0{eol}BRH:0")));

		$data = new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1);
		$coverage = new BranchCoverage(data: [$data], found: 23, hit: 11);
		assertThat((string) $coverage, equalTo(str_replace("{eol}", PHP_EOL, "$data{eol}BRF:23{eol}BRH:11")));
	}
}
