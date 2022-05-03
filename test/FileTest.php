<?php declare(strict_types=1);
namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isNull, isType, logicalNot};

/**
 * @testdox Lcov\File
 */
class FileTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$sourceFile = File::fromJson(new \stdClass);
		assertThat($sourceFile->branches, isNull());
		assertThat($sourceFile->functions, isNull());
		assertThat($sourceFile->lines, isNull());
		assertThat($sourceFile->path, isEmpty());

		// It should return an initialized instance for a non-empty map.
		$sourceFile = File::fromJson((object) [
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

	/**
	 * @testdox ->jsonSerialize()
	 */
	function testJsonSerialize(): void {
		// It should return a map with default values for a newly created instance.
		$map = (new File(""))->jsonSerialize();
		assertThat(get_object_vars($map), countOf(4));
		assertThat($map->branches, isNull());
		assertThat($map->functions, isNull());
		assertThat($map->lines, isNull());
		assertThat($map->path, isEmpty());

		// It should return a non-empty map for an initialized instance.
		$map = (new File(
			branches: new BranchCoverage,
			functions: new FunctionCoverage,
			lines: new LineCoverage,
			path: "/home/cedx/lcov.php"
		))->jsonSerialize();

		assertThat(get_object_vars($map), countOf(4));
		assertThat($map->branches, isType("object"));
		assertThat($map->functions, isType("object"));
		assertThat($map->lines, isType("object"));
		assertThat($map->path, equalTo("/home/cedx/lcov.php"));
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		// It should return a format like "SF:<path>\\nend_of_record".
		assertThat((string) new File(""), equalTo(str_replace("{eol}", PHP_EOL, "SF:{eol}end_of_record")));

		$sourceFile = new File(
			branches: new BranchCoverage,
			functions: new FunctionCoverage,
			lines: new LineCoverage,
			path: "/home/cedx/lcov.php"
		);

		$format = "SF:/home/cedx/lcov.php{eol}{$sourceFile->functions}{eol}{$sourceFile->branches}{eol}{$sourceFile->lines}{eol}end_of_record";
		assertThat((string) $sourceFile, equalTo(str_replace("{eol}", PHP_EOL, $format)));
	}
}
