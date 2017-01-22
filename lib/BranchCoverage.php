<?php
/**
 * Implementation of the `lcov\BranchCoverage` class.
 */
namespace lcov;

/**
 * Provides the coverage data of branches.
 */
class BranchCoverage {

  /**
   * @var \ArrayObject The coverage data.
   */
  private $data;

  /**
   * @var int The number of branches found.
   */
  private $found;

  /**
   * @var int The number of branches hit.
   */
  private $hit;

  /**
   * Initializes a new instance of the class.
   * @param int $found The number of branches found.
   * @param int $hit The number of branches hit.
   * @param array $data The coverage data.
   */
  public function __construct(int $found = 0, int $hit = 0, array $data = []) {
    $this->data = new \ArrayObject($data);
    $this->setFound($found);
    $this->setHit($hit);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $lines = array_map(function($item) { return (string) $item; }, $this->getData()->getArrayCopy());
    $lines[] = Token::BRANCHES_FOUND.":{$this->getFound()}";
    $lines[] = Token::BRANCHES_HIT.":{$this->getHit()}";
    return implode(PHP_EOL, $lines);
  }

  /**
   * Creates a new branch data from the specified JSON map.
   * @param mixed $map A JSON map representing a branch data.
   * @return BranchCoverage The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    $transform = function(array $data) {
      return array_filter(array_map(function($item) { return BranchData::fromJSON($item); }, $data));
    };

    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static(
      isset($map->found) && is_int($map->found) ? $map->found : 0,
      isset($map->hit) && is_int($map->hit) ? $map->hit : 0,
      isset($map->data) && is_array($map->data) ? $transform($map->data) : []
    );
  }

  /**
   * Gets the coverage data.
   * @return \ArrayObject The coverage data.
   */
  public function getData(): \ArrayObject {
    return $this->data;
  }

  /**
   * Gets the number of branches found.
   * @return int The number of branches found.
   */
  public function getFound(): int {
    return $this->found;
  }

  /**
   * Gets the number of branches hit.
   * @return int The number of branches hit.
   */
  public function getHit(): int {
    return $this->hit;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'data' => array_map(function(BranchData $item) { return $item->jsonSerialize(); }, $this->getData()->getArrayCopy()),
      'found' => $this->getFound(),
      'hit' => $this->getHit()
    ];
  }

  /**
   * Sets the coverage data.
   * @param BranchData[] $value The new coverage data.
   * @return BranchCoverage This instance.
   */
  public function setData(array $value): self {
    $this->getData()->exchangeArray($value);
    return $this;
  }

  /**
   * Sets the number of branches found.
   * @param int $value The new number of branches found.
   * @return BranchCoverage This instance.
   */
  public function setFound(int $value): self {
    $this->found = $value;
    return $this;
  }

  /**
   * Sets the number of branches hit.
   * @param int $value The new number of branches hit.
   * @return BranchCoverage This instance.
   */
  public function setHit(int $value): self {
    $this->hit = $value;
    return $this;
  }
}