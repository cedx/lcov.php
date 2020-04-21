<?php declare(strict_types=1);
namespace Lcov;

/** Provides the coverage data of functions. */
class FunctionCoverage implements \JsonSerializable {

  /** @var \ArrayObject<int, FunctionData> The coverage data. */
  private \ArrayObject $data;

  /** @var int The number of functions found. */
  private int $found;

  /** @var int The number of functions hit. */
  private int $hit;

  /**
   * Creates a new function coverage.
   * @param int $found The number of functions found.
   * @param int $hit The number of functions hit.
   * @param FunctionData[] $data The coverage data.
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
    $data = (array) $this->getData();
    return implode(PHP_EOL, [
      ...array_map(fn(FunctionData $item) => $item->toString(true), $data),
      ...array_map(fn(FunctionData $item) => $item->toString(false), $data),
      Token::functionsFound.":{$this->getFound()}",
      Token::functionsHit.":{$this->getHit()}"
    ]);
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
      isset($map->data) && is_array($map->data) ? array_map([FunctionData::class, 'fromJson'], $map->data) : []
    );
  }

  /**
   * Gets the coverage data.
   * @return \ArrayObject<int, FunctionData> The coverage data.
   */
  function getData(): \ArrayObject {
    return $this->data;
  }

  /**
   * Gets the number of functions found.
   * @return int The number of functions found.
   */
  function getFound(): int {
    return $this->found;
  }

  /**
   * Gets the number of functions hit.
   * @return int The number of functions hit.
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
      'data' => array_map(fn(FunctionData $item) => $item->jsonSerialize(), (array) $this->getData())
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
