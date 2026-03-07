<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\assertEquals;

/**
 * Tests the features of the {@see FunctionCoverage} class.
 */
#[TestDox("FunctionCoverage")]
final class FunctionCoverageTests extends TestCase {

	#[Test, TestDox("__toString()")]
	public function testToString(): void {
		// It should return a format like 'FNF:<found>\\nFNH:<hit>'.
		assertEquals("FNF:0\nFNH:0", (string) new FunctionCoverage);

		$data = new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127);
		$coverage = new FunctionCoverage(data: [$data], found: 23, hit: 11);
		assertEquals("FN:127,main\nFNDA:3,main\nFNF:23\nFNH:11", (string) $coverage);
	}
}
