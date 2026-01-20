<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides the list of tokens supported by the parser.
 */
enum Token: string {

	/**
	 * The coverage data of a branch.
	 */
	case BranchData = "BRDA";

	/**
	 * The number of branches found.
	 */
	case BranchesFound = "BRF";

	/**
	 * The number of branches hit.
	 */
	case BranchesHit = "BRH";

	/**
	 * The end of a section.
	 */
	case EndOfRecord = "end_of_record";

	/**
	 * The coverage data of a function.
	 */
	case FunctionData = "FNDA";

	/**
	 * A function name.
	 */
	case FunctionName = "FN";

	/**
	 * The number of functions found.
	 */
	case FunctionsFound = "FNF";

	/**
	 * The number of functions hit.
	 */
	case FunctionsHit = "FNH";

	/**
	 * The coverage data of a line.
	 */
	case LineData = "DA";

	/**
	 * The number of lines found.
	 */
	case LinesFound = "LF";

	/**
	 * The number of lines hit.
	 */
	case LinesHit = "LH";

	/**
	 * The path to a source file.
	 */
	case SourceFile = "SF";

	/**
	 * A test name.
	 */
	case TestName = "TN";
}
