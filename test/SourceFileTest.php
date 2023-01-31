<?php namespace lcov;

use PHPUnit\Framework\{TestCase};
use function phpunit\expect\{expect, it};

/**
 * @testdox lcov\SourceFile
 */
class SourceFileTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		it("should return an instance with default values for an empty map", function() {
			$sourceFile = SourceFile::fromJson(new \stdClass);
			expect($sourceFile->branches)->to->be->null;
			expect($sourceFile->functions)->to->be->null;
			expect($sourceFile->lines)->to->be->null;
			expect($sourceFile->path)->to->be->empty;
		});

		it("should return an initialized instance for a non-empty map", function() {
			$sourceFile = SourceFile::fromJson((object) [
				"branches" => new \stdClass,
				"functions" => new \stdClass,
				"lines" => new \stdClass,
				"path" => "/home/cedx/lcov.php"
			]);

			expect($sourceFile->branches)->to->not->be->null;
			expect($sourceFile->functions)->to->not->be->null;
			expect($sourceFile->lines)->to->not->be->null;
			expect($sourceFile->path)->to->equal("/home/cedx/lcov.php");
		});
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		it("should return a format like 'SF:<path>\\nend_of_record'", function() {
			expect((string) new SourceFile(""))->to->equal(str_replace("{eol}", PHP_EOL, "SF:{eol}end_of_record"));

			$sourceFile = new SourceFile(
				branches: new BranchCoverage,
				functions: new FunctionCoverage,
				lines: new LineCoverage,
				path: "/home/cedx/lcov.php"
			);

			$format = "SF:/home/cedx/lcov.php{eol}{$sourceFile->functions}{eol}{$sourceFile->branches}{eol}{$sourceFile->lines}{eol}end_of_record";
			expect((string) $sourceFile)->to->equal(str_replace("{eol}", PHP_EOL, $format));
		});
	}
}
