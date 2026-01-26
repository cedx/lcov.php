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
		assertEquals(strtr("FNF:0{eol}FNH:0", ["{eol}" => PHP_EOL]), (string) new FunctionCoverage);

		$data = new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127);
		$coverage = new FunctionCoverage(data: [$data], found: 23, hit: 11);
		assertEquals(strtr("FN:127,main{eol}FNDA:3,main{eol}FNF:23{eol}FNH:11", ["{eol}" => PHP_EOL]), (string) $coverage);
	}
}
