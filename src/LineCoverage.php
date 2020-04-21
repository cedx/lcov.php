<?php declare(strict_types=1);
namespace Lcov;

/** Provides the coverage data of lines.*/
class LineCoverage implements \JsonSerializable {

  /** @var \ArrayObject<int, LineData> The coverage data. */
  private \ArrayObject $data;

  /** @var int The number of lines found. */
  private int $found;

  /** @var int The number of lines hit. */
  private int $hit;

  /**
   * Creates a new line coverage.
   * @param int $found The number of lines found.
   * @param int $hit The number of lines hit.
   * @param LineData[] $data The coverage data.
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
    $lines = array_map('strval', (array) $this->getData());
    $lines[] = Token::linesFound.":{$this->getFound()}";
    $lines[] = Token::linesHit.":{$this->getHit()}";
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
      isset($map->data) && is_array($map->data) ? array_map([LineData::class, 'fromJson'], $map->data) : []
    );
  }

  /**
   * Gets the coverage data.
   * @return \ArrayObject<int, LineData> The coverage data.
   */
  function getData(): \ArrayObject {
    return $this->data;
  }

  /**
   * Gets the number of lines found.
   * @return int The number of lines found.
   */
  function getFound(): int {
    return $this->found;
  }

  /**
   * Gets the number of lines hit.
   * @return int The number of lines hit.
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
      'data' => array_map(fn(LineData $item) => $item->jsonSerialize(), (array) $this->getData())
    ];
  }

  /**
   * Sets the number of branches found.
   * @param int $value The new number of branches found.
   * @return $this This instance.
   */
  function setFound(int $value): self {
    assert($value >= 0);
    $this->found = $value;
    return $this;
  }

  /**
   * Sets the number of branches hit.
   * @param int $value The new number of branches hit.
   * @return $this This instance.
   */
  function setHit(int $value): self {
    assert($value >= 0);
    $this->hit = $value;
    return $this;
  }
}
