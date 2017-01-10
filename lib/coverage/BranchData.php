<?php
/**
 * Implementation of the `lcov\coverage\BranchData` class.
 */
namespace lcov\coverage;
use lcov\{Token};

/**
 * Provides the coverage data of branches.
 */
class BranchData {

  /**
   * @var int The branch number.
   */
  private $branchNumber = 0;

  /**
   * @var int The block number.
   */
  private $blockNumber = 0;

  /**
   * @var int The line number.
   */
  private $lineNumber = 0;

  /**
   * @var int A number indicating how often this branch was taken.
   */
  private $taken = 0;

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
    $value = "$token:{$this->getLineNumber()},{$this->getBlockNumber()},{$this->getBranchNumber()}";
    return ($taken = $this->getTaken()) > 0 ? "$value,$taken" : "$value,-";
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
   * Gets the branch number.
   * @return int The branch number.
   */
  public function getBranchNumber(): int {
    return $this->branchNumber;
  }

  /**
   * Gets the block number.
   * @return int The block number.
   */
  public function getBlockNumber(): int {
    return $this->blockNumber;
  }

  /**
   * Gets the line number.
   * @return int The line number.
   */
  public function getLineNumber(): int {
    return $this->lineNumber;
  }

  /**
   * Gets a number indicating how often this branch was taken.
   * @return int A number indicating how often this branch was taken.
   */
  public function getTaken(): int {
    return $this->taken;
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

  /**
   * Sets the branch number.
   * @param int $value The new branch number.
   * @return BranchData This instance.
   */
  public function setBranchNumber(int $value): self {
    $this->branchNumber = $value;
    return $this;
  }

  /**
   * Sets the block number.
   * @param int $value The new block number.
   * @return BranchData This instance.
   */
  public function setBlockNumber(int $value): self {
    $this->blockNumber = $value;
    return $this;
  }

  /**
   * Sets the line number.
   * @param int $value The new line number.
   * @return BranchData This instance.
   */
  public function setLineNumber(int $value): self {
    $this->lineNumber = $value;
    return $this;
  }

  /**
   * Sets a number indicating how often this branch was taken.
   * @param int $value The new number indicating how often this branch was taken.
   * @return BranchData This instance.
   */
  public function setTaken(int $value): self {
    $this->taken = $value;
    return $this;
  }
}
