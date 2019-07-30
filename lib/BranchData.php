<?php declare(strict_types=1);
namespace Lcov;

/** Provides details for branch coverage. */
class BranchData implements \JsonSerializable {

  /** @var int The block number. */
  private $blockNumber;

  /** @var int The branch number. */
  private $branchNumber;

  /** @var int The line number. */
  private $lineNumber;

  /** @var int A number indicating how often this branch was taken. */
  private $taken;

  /**
   * Creates a new branch data.
   * @param int $lineNumber The line number.
   * @param int $blockNumber The block number.
   * @param int $branchNumber The branch number.
   * @param int $taken A number indicating how often this branch was taken.
   */
  function __construct(int $lineNumber, int $blockNumber, int $branchNumber, int $taken = 0) {
    $this->lineNumber = max(0, $lineNumber);
    $this->blockNumber = max(0, $blockNumber);
    $this->branchNumber = max(0, $branchNumber);
    $this->setTaken($taken);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  function __toString(): string {
    $token = Token::branchData;
    $value = "$token:{$this->getLineNumber()},{$this->getBlockNumber()},{$this->getBranchNumber()}";
    return ($taken = $this->getTaken()) > 0 ? "$value,$taken" : "$value,-";
  }

  /**
   * Creates a new branch data from the specified JSON map.
   * @param object $map A JSON map representing a branch data.
   * @return static The instance corresponding to the specified JSON map.
   */
  static function fromJson(object $map): self {
    return new self(
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
  function getBlockNumber(): int {
    return $this->blockNumber;
  }

  /**
   * Gets the branch number.
   * @return int The branch number.
   */
  function getBranchNumber(): int {
    return $this->branchNumber;
  }

  /**
   * Gets the line number.
   * @return int The line number.
   */
  function getLineNumber(): int {
    return $this->lineNumber;
  }

  /**
   * Gets a number indicating how often this branch was taken.
   * @return int A number indicating how often this branch was taken.
   */
  function getTaken(): int {
    return $this->taken;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  function jsonSerialize(): \stdClass {
    return (object) [
      'lineNumber' => $this->getLineNumber(),
      'blockNumber' => $this->getBlockNumber(),
      'branchNumber' => $this->getBranchNumber(),
      'taken' => $this->getTaken()
    ];
  }

  /**
   * Sets a number indicating how often this branch was taken.
   * @param int $value The new number indicating how often this branch was taken.
   * @return $this This instance.
   */
  function setTaken(int $value): self {
    $this->taken = max(0, $value);
    return $this;
  }
}
