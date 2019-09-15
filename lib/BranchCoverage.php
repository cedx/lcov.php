<?php declare(strict_types=1);
namespace Lcov;

/** Provides the coverage data of branches. */
class BranchCoverage implements \JsonSerializable {

  /** @var \ArrayObject The coverage data. */
  private $data;

  /** @var int The number of branches found. */
  private $found;

  /** @var int The number of branches hit. */
  private $hit;

  /**
   * Creates a new branch coverage.
   * @param int $found The number of branches found.
   * @param int $hit The number of branches hit.
   * @param BranchData[] $data The coverage data.
   */
  function __construct(int $found = 0, int $hit = 0, array $data = []) {
    $this->data = new \ArrayObject($data);
    $this->setFound($found)->setHit($hit);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  function __toString(): string {
    $lines = array_map('strval', $this->getData()->getArrayCopy());
    $lines[] = Token::branchesFound.":{$this->getFound()}";
    $lines[] = Token::branchesHit.":{$this->getHit()}";
    return implode(PHP_EOL, $lines);
  }

  /**
   * Creates a new branch data from the specified JSON object.
   * @param object $map A JSON object representing a branch data.
   * @return self The instance corresponding to the specified JSON object.
   */
  static function fromJson(object $map): self {
    return new self(
      isset($map->found) && is_int($map->found) ? $map->found : 0,
      isset($map->hit) && is_int($map->hit) ? $map->hit : 0,
      isset($map->data) && is_array($map->data) ? array_map([BranchData::class, 'fromJson'], $map->data) : []
    );
  }

  /**
   * Gets the coverage data.
   * @return \ArrayObject The coverage data.
   */
  function getData(): \ArrayObject {
    return $this->data;
  }

  /**
   * Gets the number of branches found.
   * @return int The number of branches found.
   */
  function getFound(): int {
    return $this->found;
  }

  /**
   * Gets the number of branches hit.
   * @return int The number of branches hit.
   */
  function getHit(): int {
    return $this->hit;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  function jsonSerialize(): \stdClass {
    return (object) [
      'found' => $this->getFound(),
      'hit' => $this->getHit(),
      'data' => array_map(function(BranchData $item) {
        return $item->jsonSerialize();
      }, $this->getData()->getArrayCopy())
    ];
  }

  /**
   * Sets the number of branches found.
   * @param int $value The new number of branches found.
   * @return $this This instance.
   */
  function setFound(int $value): self {
    $this->found = max(0, $value);
    return $this;
  }

  /**
   * Sets the number of branches hit.
   * @param int $value The new number of branches hit.
   * @return $this This instance.
   */
  function setHit(int $value): self {
    $this->hit = max(0, $value);
    return $this;
  }
}
