<?php namespace lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\TestDox;
use function PHPUnit\Framework\{assertThat, equalTo, isEmpty, isNull, logicalNot};

/**
 * Tests the features of the {@see SourceFile} class.
 */
#[TestDox('lcov\SourceFile')]
final class SourceFileTest extends TestCase {

	#[TestDox("::fromJson()")]
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$sourceFile = SourceFile::fromJson(new \stdClass);
		assertThat($sourceFile->branches, isNull());
		assertThat($sourceFile->functions, isNull());
		assertThat($sourceFile->lines, isNull());
		assertThat($sourceFile->path, isEmpty());

		// It should return an initialized instance for a non-empty map.
		$sourceFile = SourceFile::fromJson((object) [
			"branches" => new \stdClass,
			"functions" => new \stdClass,
			"lines" => new \stdClass,
			"path" => "/home/cedx/lcov.php"
		]);

		assertThat($sourceFile->branches, logicalNot(isNull()));
		assertThat($sourceFile->functions, logicalNot(isNull()));
		assertThat($sourceFile->lines, logicalNot(isNull()));
		assertThat($sourceFile->path, equalTo("/home/cedx/lcov.php"));
	}

	#[TestDox("->__toString()")]
	function testToString(): void {
		// It should return a format like 'SF:<path>\\nend_of_record'.
		assertThat((string) new SourceFile(""), equalTo(str_replace("{eol}", PHP_EOL, "SF:{eol}end_of_record")));

		$sourceFile = new SourceFile(
			branches: new BranchCoverage,
			functions: new FunctionCoverage,
			lines: new LineCoverage,
			path: "/home/cedx/lcov.php"
		);

		$format = "SF:/home/cedx/lcov.php{eol}{$sourceFile->functions}{eol}{$sourceFile->branches}{eol}{$sourceFile->lines}{eol}end_of_record";
		assertThat((string) $sourceFile, equalTo(str_replace("{eol}", PHP_EOL, $format)));
	}
}
