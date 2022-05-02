<?php declare(strict_types=1);
namespace Lcov;

/**
 * Provides the list of tokens supported by the parser.
 */
enum Token: string {

	/** The coverage data of a branch. */
	case branchData = "BRDA";

	/** The number of branches found. */
	case branchesFound = "BRF";

	/** The number of branches hit. */
	case branchesHit = "BRH";

	/** The end of a section. */
	case endOfRecord = "end_of_record";

	/** The coverage data of a function. */
	case functionData = "FNDA";

	/** A function name. */
	case functionName = "FN";

	/** The number of functions found. */
	case functionsFound = "FNF";

	/** The number of functions hit. */
	case functionsHit = "FNH";

	/** The coverage data of a line. */
	case lineData = "DA";

	/** The number of lines found. */
	case linesFound = "LF";

	/** The number of lines hit. */
	case linesHit = "LH";

	/** The path to a source file. */
	case sourceFile = "SF";

	/** A test name. */
	case testName = "TN";
}
