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
}
