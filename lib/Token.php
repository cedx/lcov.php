<?php declare(strict_types=1);
namespace Lcov;

/**
 * Provides the list of tokens supported by the parser.
 */
abstract class Token {

	/**
	 * The coverage data of a branch.
	 * @var string
	 */
	final const branchData = "BRDA";

	/**
	 * The number of branches found.
	 * @var string
	 */
	final const branchesFound = "BRF";

	/**
	 * The number of branches hit.
	 * @var string
	 */
	final const branchesHit = "BRH";

	/**
	 * The end of a section.
	 * @var string
	 */
	final const endOfRecord = "end_of_record";

	/**
	 * The coverage data of a function.
	 * @var string
	 */
	final const functionData = "FNDA";

	/**
	 * A function name.
	 * @var string
	 */
	final const functionName = "FN";

	/**
	 * The number of functions found.
	 * @var string
	 */
	final const functionsFound = "FNF";

	/**
	 * The number of functions hit.
	 * @var string
	 */
	final const functionsHit = "FNH";

	/**
	 * The coverage data of a line.
	 * @var string
	 */
	final const lineData = "DA";

	/**
	 * The number of lines found.
	 * @var string
	 */
	final const linesFound = "LF";

	/**
	 * The number of lines hit.
	 * @var string
	 */
	final const linesHit = "LH";

	/**
	 * The path to a source file.
	 * @var string
	 */
	final const sourceFile = "SF";

	/**
	 * A test name.
	 * @var string
	 */
	final const testName = "TN";
}
