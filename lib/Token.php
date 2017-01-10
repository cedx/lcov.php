<?php
/**
 * Implementation of the `lcov\Token` enumeration.
 */
namespace lcov;
use cedx\{EnumTrait};

/**
 * Provides the list of tokens supported by the parser.
 */
final class Token {
  use EnumTrait;

  /**
   * @var string The coverage data of a branch.
   */
  const BRANCH_DATA = 'BRDA';

  /**
   * @var string The number of branches found.
   */
  const BRANCHES_FOUND = 'BRF';

  /**
   * @var string The number of branches hit.
   */
  const BRANCHES_HIT = 'BRH';

  /**
   * @var string The end of a section.
   */
  const END_OF_RECORD = 'end_of_record';

  /**
   * @var string The coverage data of a function.
   */
  const FUNCTION_DATA = 'FNDA';

  /**
   * @var string A function name.
   */
  const FUNCTION_NAME = 'FN';

  /**
   * @var string The number of functions found.
   */
  const FUNCTIONS_FOUND = 'FNF';

  /**
   * @var string The number of functions hit.
   */
  const FUNCTIONS_HIT = 'FNH';

  /**
   * @var string The coverage data of a line.
   */
  const LINE_DATA = 'DA';

  /**
   * @var string The number of lines found.
   */
  const LINES_FOUND = 'LF';

  /**
   * @var string The number of lines hit.
   */
  const LINES_HIT = 'LH';

  /**
   * @var string The path to a source file.
   */
  const SOURCE_FILE = 'SF';

  /**
   * @var string A test name.
   */
  const TEST_NAME = 'TN';
}
