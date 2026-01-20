<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf};

/**
 * Tests the features of the {@see LineCoverage} class.
 */
#[TestDox("LineCoverage")]
final class LineCoverageTests extends TestCase {

	#[Test, TestDox("__toString()")]
	function testToString(): void {
		// It should return a format like 'LF:<found>\\nLH:<hit>'.
		assertThat((string) new LineCoverage, equalTo(strtr("LF:0{eol}LH:0", ["{eol}" => PHP_EOL])));

		$data = new LineData(executionCount: 3, lineNumber: 127);
		$coverage = new LineCoverage(data: [$data], found: 23, hit: 11);
		assertThat((string) $coverage, equalTo(strtr("$data{eol}LF:23{eol}LH:11", ["{eol}" => PHP_EOL])));
	}
}
