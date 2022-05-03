<?php declare(strict_types=1);
use Lcov\{File, FunctionCoverage, LineCoverage, LineData, Report};

/** Formats coverage data as LCOV report. */
function formatReport(): void {
	$lineCoverage = new LineCoverage(2, 2, [
		new LineData(6, 2, "PF4Rz2r7RTliO9u6bZ7h6g"),
		new LineData(7, 2, "yGMB6FhEEAd8OyASe3Ni1w")
	]);

	$file = (new File("/home/cedx/lcov.php/fixture.php"))
		->setFunctions(new FunctionCoverage(1, 1))
		->setLines($lineCoverage);

	$report = new Report("Example", [$file]);
	echo $report;
}

/** Parses a LCOV report to coverage data. */
function parseReport(): void {
	try {
		$coverage = file_get_contents("/path/to/lcov.info");
		$report = Report::fromString($coverage);

		$count = count($report->sourceFiles);
		echo "The coverage report contains $count source files:";
		print_r($report->jsonSerialize());
	}

	catch (UnexpectedValueException $e) {
		echo "An error occurred: ", $e->getMessage();
	}
}
