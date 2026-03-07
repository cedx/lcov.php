<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\assertEquals;

/**
 * Tests the features of the {@see BranchCoverage} class.
 */
#[TestDox("BranchCoverage")]
final class BranchCoverageTests extends TestCase {

	#[Test, TestDox("__toString()")]
	public function testToString(): void {
		// It should return a format like 'BRF:<found>\\nBRH:<hit>'.
		assertEquals("BRF:0\nBRH:0", (string) new BranchCoverage);

		$data = new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1);
		$coverage = new BranchCoverage(data: [$data], found: 23, hit: 11);
		assertEquals("$data\nBRF:23\nBRH:11", (string) $coverage);
	}
}
