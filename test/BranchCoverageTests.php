<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertThat, equalTo};

/**
 * Tests the features of the {@see BranchCoverage} class.
 */
#[TestDox("BranchCoverage")]
final class BranchCoverageTests extends TestCase {

	#[Test, TestDox("__toString()")]
	public function testToString(): void {
		// It should return a format like 'BRF:<found>\\nBRH:<hit>'.
		assertThat((string) new BranchCoverage, equalTo(strtr("BRF:0{eol}BRH:0", ["{eol}" => PHP_EOL])));

		$data = new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1);
		$coverage = new BranchCoverage(data: [$data], found: 23, hit: 11);
		assertThat((string) $coverage, equalTo(strtr("$data{eol}BRF:23{eol}BRH:11", ["{eol}" => PHP_EOL])));
	}
}
