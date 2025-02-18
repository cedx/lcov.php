<?php declare(strict_types=1);
use lcov\{FunctionCoverage, LineCoverage, LineData, Report, SourceFile};

// Formats coverage data as LCOV report.
$sourceFile = new SourceFile(
	path: "/home/cedx/lcov.php/fixture.php",
	functions: new FunctionCoverage(found: 1, hit: 1),
	lines: new LineCoverage(found: 2, hit: 2, data: [
		new LineData(lineNumber: 6, executionCount: 2, checksum: "PF4Rz2r7RTliO9u6bZ7h6g"),
		new LineData(lineNumber: 7, executionCount: 2, checksum: "yGMB6FhEEAd8OyASe3Ni1w")
	])
);

$report = new Report("Example", [$sourceFile]);
print $report;
