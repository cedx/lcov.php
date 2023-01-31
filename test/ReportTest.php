<?php namespace lcov;

use PHPUnit\Framework\{TestCase};
use function phpunit\expect\{expect, it};

/**
 * @testdox lcov\Report
 */
class ReportTest extends TestCase {

	/**
	 * @testdox ::fromJson()
	 */
	function testFromJson(): void {
		it("should return an instance with default values for an empty map", function() {
			$report = Report::fromJson(new \stdClass);
			expect($report->sourceFiles)->to->be->empty;
			expect($report->testName)->to->be->empty;
		});

		it("should return an initialized instance for a non-empty map", function() {
			$report = Report::fromJson((object) ["sourceFiles" => [new \stdClass], "testName" => "LcovTest"]);
			expect($report->sourceFiles)->to->have->lengthOf(1);
			expect($report->sourceFiles[0])->to->be->an->instanceOf(SourceFile::class);
			expect($report->testName)->to->equal("LcovTest");
		});
	}

	/**
	 * @testdox ::parse()
	 */
	function testParse(): void {
		$report = Report::parse(file_get_contents("test/fixture/lcov.info") ?: "");

		it("should have a test name", function() use ($report) {
			expect($report->testName)->to->equal("Example");
		});

		it("should contain three source files", function() use ($report) {
			expect($report->sourceFiles)->to->have->lengthOf(3);
			expect($report->sourceFiles[0])->to->be->an->instanceOf(SourceFile::class);
			expect($report->sourceFiles[0]->path)->to->equal("/home/cedx/lcov.php/fixture.php");
			expect($report->sourceFiles[1]->path)->to->equal("/home/cedx/lcov.php/func1.php");
			expect($report->sourceFiles[2]->path)->to->equal("/home/cedx/lcov.php/func2.php");
		});

		it("should have detailed branch coverage", function() use ($report) {
			/** @var BranchCoverage $branches */
			$branches = $report->sourceFiles[1]->branches;
			expect($branches->data)->to->have->lengthOf(4);
			expect($branches->found)->to->equal(4);
			expect($branches->hit)->to->equal(4);

			[$data] = $branches->data;
			expect($data)->to->be->an->instanceOf(BranchData::class);
			expect($data->lineNumber)->to->equal(8);
		});

		it("should have detailed function coverage", function() use ($report) {
			/** @var FunctionCoverage $functions */
			$functions = $report->sourceFiles[1]->functions;
			expect($functions->data)->to->have->lengthOf(1);
			expect($functions->found)->to->equal(1);
			expect($functions->hit)->to->equal(1);

			[$data] = $functions->data;
			expect($data)->to->be->an->instanceOf(FunctionData::class);
			expect($data->functionName)->to->equal("func1");
		});

		it("should have detailed line coverage", function() use ($report) {
			/** @var LineCoverage $lines */
			$lines = $report->sourceFiles[1]->lines;
			expect($lines->data)->to->have->lengthOf(9);
			expect($lines->found)->to->equal(9);
			expect($lines->hit)->to->equal(9);

			[$data] = $lines->data;
			expect($data)->to->be->an->instanceOf(LineData::class);
			expect($data->checksum)->to->equal("5kX7OTfHFcjnS98fjeVqNA");
		});

		it("should throw an exception if the report is invalid or empty", function() {
			expect(fn() => Report::parse("TN:Example"))->to->throw(\InvalidArgumentException::class);
		});
	}

	/**
	 * @testdox ->__toString()
	 */
	function testToString(): void {
		it("should return a format like 'TN:<testName>'", function() {
			expect((string) new Report(""))->to->be->empty;

			$sourceFile = new SourceFile("");
			expect((string) new Report("LcovTest", [$sourceFile]))->to->equal(str_replace("{eol}", PHP_EOL, "TN:LcovTest{eol}$sourceFile"));
		});
	}
}
