<?php
use Lcov\Report;

/**
 * Parses a LCOV report to coverage data.
 */
try {
	$report = Report::fromString(file_get_contents("/path/to/lcov.info"));
	$count = count($report->sourceFiles);
	print "The coverage report contains $count source files:" . PHP_EOL;
	print json_encode($report);
}

catch (UnexpectedValueException $e) {
	print $e->getMessage();
}
