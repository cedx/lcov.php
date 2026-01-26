<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertCount, assertEmpty, assertEquals, assertInstanceOf, assertNull};

/**
 * Tests the features of the {@see Report} class.
 */
#[TestDox("Report")]
final class ReportTests extends TestCase {

	/**
	 * The test fixture.
	 */
	private static string $coverage;

	/**
	 * Method invoked before the first test is run.
	 */
	public static function setUpBeforeClass(): void {
		self::$coverage = file_get_contents("res/Lcov.info") ?: "";
	}

	#[Test, TestDox("parse()")]
	public function parse(): void {
		$report = Report::parse(self::$coverage);

		// It should have a test name.
		assertEquals("Example", $report->testName);

		// It should contain three source files.
		assertCount(3, $report->sourceFiles);
		assertInstanceOf(SourceFile::class, $report->sourceFiles[0]);
		assertEquals("/home/cedx/lcov.php/fixture.php", $report->sourceFiles[0]->path);
		assertEquals("/home/cedx/lcov.php/func1.php", $report->sourceFiles[1]->path);
		assertEquals("/home/cedx/lcov.php/func2.php", $report->sourceFiles[2]->path);

		// It should have detailed branch coverage.
		/** @var BranchCoverage $branches */
		$branches = $report->sourceFiles[1]->branches;
		assertCount(4, $branches->data);
		assertEquals(4, $branches->found);
		assertEquals(4, $branches->hit);

		[$data] = $branches->data;
		assertInstanceOf(BranchData::class, $data);
		assertEquals(8, $data->lineNumber);

		// It should have detailed function coverage.
		/** @var FunctionCoverage $functions */
		$functions = $report->sourceFiles[1]->functions;
		assertCount(1, $functions->data);
		assertEquals(1, $functions->found);
		assertEquals(1, $functions->hit);

		[$data] = $functions->data;
		assertInstanceOf(FunctionData::class, $data);
		assertEquals("func1", $data->functionName);

		// It should have detailed line coverage.
		/** @var LineCoverage $lines */
		$lines = $report->sourceFiles[1]->lines;
		assertCount(9, $lines->data);
		assertEquals(9, $lines->found);
		assertEquals(9, $lines->hit);

		[$data] = $lines->data;
		assertInstanceOf(LineData::class, $data);
		assertEquals("5kX7OTfHFcjnS98fjeVqNA", $data->checksum);

		// It should throw an exception if the report is invalid or empty.
		$this->expectException(\InvalidArgumentException::class);
		Report::parse("TN:Example");
	}

	#[Test, TestDox("tryParse()")]
	public function tryParse(): void {
		assertInstanceOf(Report::class, Report::tryParse(self::$coverage));
		assertNull(Report::tryParse("TN:Example"));
	}

	#[Test, TestDox("__toString()")]
	public function testToString(): void {
		// It should return a format like 'TN:<testName>'.
		assertEmpty((string) new Report(""));

		$sourceFile = new SourceFile("");
		assertEquals(strtr("TN:LcovTest{eol}$sourceFile", ["{eol}" => PHP_EOL]), (string) new Report("LcovTest", [$sourceFile]));
	}
}
