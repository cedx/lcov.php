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
		$file = File::fromJson(new \stdClass);
		assertThat($file->branches, isNull());
		assertThat($file->functions, isNull());
		assertThat($file->lines, isNull());
		assertThat($file->path, isEmpty());

		// It should return an initialized instance for a non-empty map.
		$file = File::fromJson((object) [
			"branches" => new \stdClass,
			"functions" => new \stdClass,
			"lines" => new \stdClass,
			"path" => "/home/cedx/lcov.php"
		]);

		assertThat($file->branches, logicalNot(isNull()));
		assertThat($file->functions, logicalNot(isNull()));
		assertThat($file->lines, logicalNot(isNull()));
		assertThat($file->path, equalTo("/home/cedx/lcov.php"));
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

		$file = new File(
			branches: new BranchCoverage,
			functions: new FunctionCoverage,
			lines: new LineCoverage,
			path: "/home/cedx/lcov.php"
		);

		$format = "SF:/home/cedx/lcov.php{eol}{$file->functions}{eol}{$file->branches}{eol}{$file->lines}{eol}end_of_file";
		assertThat((string) $file, equalTo(str_replace("{eol}", PHP_EOL, $format)));
	}
}
