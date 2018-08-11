<?php
declare(strict_types=1);
namespace Lcov;

/**
 * Provides the coverage data of lines.
 */
class LineCoverage implements \JsonSerializable {

  /**
   * @var \ArrayObject The coverage data.
   */
  private $data;

  /**
   * @var int The number of lines found.
   */
  private $found;

  /**
   * @var int The number of lines hit.
   */
  private $hit;

  /**
   * Initializes a new instance of the class.
   * @param int $found The number of lines found.
   * @param int $hit The number of lines hit.
   * @param LineData[] $data The coverage data.
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
    $lines = array_map('strval', $this->getData()->getArrayCopy());
    $lines[] = Token::LINES_FOUND.":{$this->getFound()}";
    $lines[] = Token::LINES_HIT.":{$this->getHit()}";
    return implode(PHP_EOL, $lines);
  }

  /**
   * Creates a new branch data from the specified JSON map.
   * @param object $map A JSON map representing a branch data.
   * @return self The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJson(object $map): self {
    $transform = function($data) {
      return array_map([LineData::class, 'fromJson'], $data);
    };

    return new static(
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
   * Gets the number of lines found.
   * @return int The number of lines found.
   */
  public function getFound(): int {
    return $this->found;
  }

  /**
   * Gets the number of lines hit.
   * @return int The number of lines hit.
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
      'found' => $this->getFound(),
      'hit' => $this->getHit(),
      'data' => array_map(function(LineData $item) {
        return $item->jsonSerialize();
      }, $this->getData()->getArrayCopy())
    ];
  }

  /**
   * Sets the number of branches found.
   * @param int $value The new number of branches found.
   * @return self This instance.
   */
  public function setFound(int $value): self {
    $this->found = max(0, $value);
    return $this;
  }

  /**
   * Sets the number of branches hit.
   * @param int $value The new number of branches hit.
   * @return self This instance.
   */
  public function setHit(int $value): self {
    $this->hit = max(0, $value);
    return $this;
  }
}
