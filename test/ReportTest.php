<?php declare(strict_types=1);
namespace lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertThat, countOf, equalTo, isEmpty, isInstanceOf, isNull};

/**
 * Tests the features of the {@see Report} class.
 */
#[TestDox("Report")]
final class ReportTest extends TestCase {

	#[Test, TestDox("fromJson()")]
	function fromJson(): void {
		// It should return an instance with default values for an empty map.
		$report = Report::fromJson(new \stdClass);
		assertThat($report->sourceFiles, isEmpty());
		assertThat($report->testName, isEmpty());

		// It should return an initialized instance for a non-empty map.
		$report = Report::fromJson((object) ["sourceFiles" => [new \stdClass], "testName" => "LcovTest"]);
		assertThat($report->sourceFiles, countOf(1));
		assertThat($report->sourceFiles[0], isInstanceOf(SourceFile::class));
		assertThat($report->testName, equalTo("LcovTest"));
	}

	#[Test, TestDox("parse()")]
	function parse(): void {
		$report = Report::parse(file_get_contents("res/lcov.info") ?: "");

		// It should have a test name.
		assertThat($report->testName, equalTo("Example"));

		// It should contain three source files.
		assertThat($report->sourceFiles, countOf(3));
		assertThat($report->sourceFiles[0], isInstanceOf(SourceFile::class));
		assertThat($report->sourceFiles[0]->path, equalTo("/home/cedx/lcov.php/fixture.php"));
		assertThat($report->sourceFiles[1]->path, equalTo("/home/cedx/lcov.php/func1.php"));
		assertThat($report->sourceFiles[2]->path, equalTo("/home/cedx/lcov.php/func2.php"));

		// It should have detailed branch coverage.
		/** @var BranchCoverage $branches */
		$branches = $report->sourceFiles[1]->branches;
		assertThat($branches->data, countOf(4));
		assertThat($branches->found, equalTo(4));
		assertThat($branches->hit, equalTo(4));

		[$data] = $branches->data;
		assertThat($data, isInstanceOf(BranchData::class));
		assertThat($data->lineNumber, equalTo(8));

		// It should have detailed function coverage.
		/** @var FunctionCoverage $functions */
		$functions = $report->sourceFiles[1]->functions;
		assertThat($functions->data, countOf(1));
		assertThat($functions->found, equalTo(1));
		assertThat($functions->hit, equalTo(1));

		[$data] = $functions->data;
		assertThat($data, isInstanceOf(FunctionData::class));
		assertThat($data->functionName, equalTo("func1"));

		// It should have detailed line coverage.
		/** @var LineCoverage $lines */
		$lines = $report->sourceFiles[1]->lines;
		assertThat($lines->data, countOf(9));
		assertThat($lines->found, equalTo(9));
		assertThat($lines->hit, equalTo(9));

		[$data] = $lines->data;
		assertThat($data, isInstanceOf(LineData::class));
		assertThat($data->checksum, equalTo("5kX7OTfHFcjnS98fjeVqNA"));

		// It should throw an exception if the report is invalid or empty.
		$this->expectException(\InvalidArgumentException::class);
		Report::parse("TN:Example");
	}

	#[Test, TestDox("tryParse()")]
	function tryParse(): void {
		assertThat(Report::tryParse("TN:Example"), isNull());
	}

	#[Test, TestDox("__toString()")]
	function testToString(): void {
		// It should return a format like 'TN:<testName>'.
		assertThat((string) new Report(""), isEmpty());

		$sourceFile = new SourceFile("");
		assertThat((string) new Report("LcovTest", [$sourceFile]), equalTo(strtr("TN:LcovTest{eol}$sourceFile", ["{eol}" => PHP_EOL])));
	}
}
