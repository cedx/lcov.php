<?php
/**
 * Implementation of the `lcov\coverage\FunctionCoverage` class.
 */
namespace lcov\coverage;
use lcov\{Token};

/**
 * Provides the coverage data of functions.
 */
class FunctionCoverage {

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    foreach ($config as $property => $value) {
      $setter = "set$property";
      if(method_exists($this, $setter)) $this->$setter($value);
    }
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $token = Token::BRANCH_DATA;
    return "$token:{$this->getLineNumber()},{$this->getBlockNumber()},{$this->getBranchNumber()}";
  }

  /**
   * Creates a new branch data from the specified JSON map.
   * @param mixed $map A JSON map representing a branch data.
   * @return BranchData The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static([
      'branchNumber' => isset($map->branch) && is_int($map->branch) ? $map->branch : 0,
      'blockNumber' => isset($map->block) && is_int($map->block) ? $map->block : 0,
      'lineNumber' => isset($map->line) && is_int($map->line) ? $map->line : 0,
      'taken' => isset($map->taken) && is_int($map->taken) ? $map->taken : 0
    ]);
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'branch' => $this->getBranchNumber(),
      'block' => $this->getBlockNumber(),
      'line' => $this->getLineNumber(),
      'taken' => $this->getTaken()
    ];
  }
}
