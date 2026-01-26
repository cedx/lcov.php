<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\assertEquals;

/**
 * Tests the features of the {@see SourceFile} class.
 */
#[TestDox("SourceFile")]
final class SourceFileTests extends TestCase {

	#[Test, TestDox("__toString()")]
	public function testToString(): void {
		// It should return a format like 'SF:<path>\\nend_of_record'.
		assertEquals(strtr("SF:{eol}end_of_record", ["{eol}" => PHP_EOL]), (string) new SourceFile(""));

		$sourceFile = new SourceFile(
			branches: new BranchCoverage,
			functions: new FunctionCoverage,
			lines: new LineCoverage,
			path: "/home/cedx/lcov.php"
		);

		$format = "SF:/home/cedx/lcov.php{eol}$sourceFile->functions{eol}$sourceFile->branches{eol}$sourceFile->lines{eol}end_of_record";
		assertEquals(strtr($format, ["{eol}" => PHP_EOL]), (string) $sourceFile);
	}
}
