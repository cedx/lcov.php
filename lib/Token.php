<?php
declare(strict_types=1);
namespace Lcov;

/**
 * Provides the list of tokens supported by the parser.
 */
abstract class Token {

  /**
   * @var string The coverage data of a branch.
   */
  public const BRANCH_DATA = 'BRDA';

  /**
   * @var string The number of branches found.
   */
  public const BRANCHES_FOUND = 'BRF';

  /**
   * @var string The number of branches hit.
   */
  public const BRANCHES_HIT = 'BRH';

  /**
   * @var string The end of a section.
   */
  public const END_OF_RECORD = 'end_of_record';

  /**
   * @var string The coverage data of a function.
   */
  public const FUNCTION_DATA = 'FNDA';

  /**
   * @var string A function name.
   */
  public const FUNCTION_NAME = 'FN';

  /**
   * @var string The number of functions found.
   */
  public const FUNCTIONS_FOUND = 'FNF';

  /**
   * @var string The number of functions hit.
   */
  public const FUNCTIONS_HIT = 'FNH';

  /**
   * @var string The coverage data of a line.
   */
  public const LINE_DATA = 'DA';

  /**
   * @var string The number of lines found.
   */
  public const LINES_FOUND = 'LF';

  /**
   * @var string The number of lines hit.
   */
  public const LINES_HIT = 'LH';

  /**
   * @var string The path to a source file.
   */
  public const SOURCE_FILE = 'SF';

  /**
   * @var string A test name.
   */
  public const TEST_NAME = 'TN';
}
