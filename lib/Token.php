<?php declare(strict_types=1);
namespace Lcov;

/** Provides the list of tokens supported by the parser. */
abstract class Token {

	/** @var string The coverage data of a branch. */
	const branchData = "BRDA";

	/** @var string The number of branches found. */
	const branchesFound = "BRF";

	/** @var string The number of branches hit. */
	const branchesHit = "BRH";

	/** @var string The end of a section. */
	const endOfRecord = "end_of_record";

	/** @var string The coverage data of a function. */
	const functionData = "FNDA";

	/** @var string A function name. */
	const functionName = "FN";

	/** @var string The number of functions found. */
	const functionsFound = "FNF";

	/** @var string The number of functions hit. */
	const functionsHit = "FNH";

	/** @var string The coverage data of a line. */
	const lineData = "DA";

	/** @var string The number of lines found. */
	const linesFound = "LF";

	/** @var string The number of lines hit. */
	const linesHit = "LH";

	/** @var string The path to a source file. */
	const sourceFile = "SF";

	/** @var string A test name. */
	const testName = "TN";
}
