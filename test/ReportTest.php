<?php declare(strict_types=1);
namespace Lcov;

use PHPUnit\Framework\{TestCase};
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf, isType};

/**
 * @testdox Lcov\Report
 */
class ReportTest extends TestCase {

	/**
	 * @testdox ::fromString()
	 */
	function testFromString(): void {
		// It should have a test name.
		$report = Report::fromString(file_get_contents("test/fixtures/lcov.info"));
		assertThat($report->testName, equalTo("Example"));

		// It should contain three files.
		assertThat($report->files, countOf(3));
		assertThat($report->files[0], isInstanceOf(File::class));
		assertThat($report->files[0]->path, equalTo("/home/cedx/lcov.php/fixture.php"));
		assertThat($report->files[1]->path, equalTo("/home/cedx/lcov.php/func1.php"));
		assertThat($report->files[2]->path, equalTo("/home/cedx/lcov.php/func2.php"));

		// It should have detailed branch coverage.
		$branches = $report->files[1]->branches;
		assertThat($branches->data, countOf(4));
		assertThat($branches->found, equalTo(4));
		assertThat($branches->hit, equalTo(4));

		[$data] = $branches->data;
		assertThat($data, isInstanceOf(BranchData::class));
		assertThat($data->lineNumber, equalTo(8));

		// It should have detailed function coverage.
		$functions = $report->files[1]->functions;
		assertThat($functions->data, countOf(1));
		assertThat($functions->found, equalTo(1));
		assertThat($functions->hit, equalTo(1));

		[$data] = $functions->data;
		assertThat($data, isInstanceOf(FunctionData::class));
		assertThat($data->functionName, equalTo("func1"));

		// It should have detailed line coverage.
		$lines = $report->files[1]->lines;
		assertThat($lines->data, countOf(9));
		assertThat($lines->found, equalTo(9));
		assertThat($lines->hit, equalTo(9));

		[$data] = $lines->data;
		assertThat($data, isInstanceOf(LineData::class));
		assertThat($data->checksum, equalTo("5kX7OTfHFcjnS98fjeVqNA"));

		// It should throw an exception if the report is invalid or empty.
		$this->expectException(\UnexpectedValueException::class);
		Report::fromString("TN:Example");
	}

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		// It should return an instance with default values for an empty map.
		$report = Report::fromJson(new \stdClass);
		assertThat($report->files, isEmpty());
		assertThat($report->testName, isEmpty());

		// It should return an initialized instance for a non-empty map.
		$report = Report::fromJson((object) ["files" => [new \stdClass], "testName" => "LcovTest"]);
		assertThat($report->files, countOf(1));
		assertThat($report->files[0], isInstanceOf(File::class));
		assertThat($report->testName, equalTo("LcovTest"));
	}

	/**
	 * @testdox ->jsonSerialize()
	 */
	function testJsonSerialize(): void {
		// It should return a map with default values for a newly created instance.
		$map = (new Report(""))->jsonSerialize();
		assertThat(get_object_vars($map), countOf(2));
		assertThat($map->files, isEmpty());
		assertThat($map->testName, isEmpty());

		// It should return a non-empty map for an initialized instance.
		$map = (new Report("LcovTest", [new File("")]))->jsonSerialize();
		assertThat(get_object_vars($map), countOf(2));
		assertThat($map->files, countOf(1));
		assertThat($map->files[0], isType("object"));
		assertThat($map->testName, equalTo("LcovTest"));
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		// It should return a format like "TN:<testName>".
		assertThat((string) new Report(""), isEmpty());

		$file = new File("");
		assertThat((string) new Report("LcovTest", [$file]), equalTo(str_replace("{eol}", PHP_EOL, "TN:LcovTest{eol}$file")));
	}
}
