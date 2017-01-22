<?php
/**
 * Implementation of the `lcov\BranchData` class.
 */
namespace lcov;

/**
 * Provides details for branch coverage.
 */
class BranchData {

  /**
   * @var int The block number.
   */
  private $blockNumber;

  /**
   * @var int The branch number.
   */
  private $branchNumber;

  /**
   * @var int The line number.
   */
  private $lineNumber;

  /**
   * @var int A number indicating how often this branch was taken.
   */
  private $taken;

  /**
   * Initializes a new instance of the class.
   * @param int $lineNumber The line number.
   * @param int $blockNumber The block number.
   * @param int $branchNumber The branch number.
   * @param int $taken A number indicating how often this branch was taken.
   */
  public function __construct(int $lineNumber = 0, int $blockNumber = 0, int $branchNumber = 0, int $taken = 0) {
    $this->setLineNumber($lineNumber);
    $this->setBlockNumber($blockNumber);
    $this->setBranchNumber($branchNumber);
    $this->setTaken($taken);
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
    return !is_object($map) ? null : new static(
      isset($map->lineNumber) && is_int($map->lineNumber) ? $map->lineNumber : 0,
      isset($map->blockNumber) && is_int($map->blockNumber) ? $map->blockNumber : 0,
      isset($map->branchNumber) && is_int($map->branchNumber) ? $map->branchNumber : 0,
      isset($map->taken) && is_int($map->taken) ? $map->taken : 0
    );
  }

  /**
   * Gets the block number.
   * @return int The block number.
   */
  public function getBlockNumber(): int {
    return $this->blockNumber;
  }

  /**
   * Gets the branch number.
   * @return int The branch number.
   */
  public function getBranchNumber(): int {
    return $this->branchNumber;
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
      'blockNumber' => $this->getBlockNumber(),
      'branchNumber' => $this->getBranchNumber(),
      'lineNumber' => $this->getLineNumber(),
      'taken' => $this->getTaken()
    ];
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
   * Sets the branch number.
   * @param int $value The new branch number.
   * @return BranchData This instance.
   */
  public function setBranchNumber(int $value): self {
    $this->branchNumber = $value;
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