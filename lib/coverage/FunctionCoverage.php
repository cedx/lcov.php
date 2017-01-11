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
   * @var FunctionData[] The coverage data.
   */
  private $data = [];

  /**
   * @var int The number of functions found.
   */
  private $found = 0;

  /**
   * @var int The number of functions hit.
   */
  private $hit = 0;

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
    $data = $this->getData();

    $lines = array_map(function(FunctionData $item) { return $item->toString(true); }, $data);
    $lines = array_merge($lines, array_map(function(FunctionData $item) { return $item->toString(false); }, $data));
    $lines[] = Token::FUNCTIONS_FOUND.":{$this->getFound()}";
    $lines[] = Token::FUNCTIONS_HIT.":{$this->getHit()}";

    return implode(PHP_EOL, $lines);
  }

  /**
   * Creates a new branch data from the specified JSON map.
   * @param mixed $map A JSON map representing a branch data.
   * @return FunctionCoverage The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    $transform = function(array $data) {
      $items = array_map(function($item) { return FunctionData::fromJSON($item); }, $data);
      return array_filter($items, function($item) { return isset($item); });
    };

    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static([
      'data' => isset($map->data) && is_array($map->data) ? $transform($map->data) : [],
      'found' => isset($map->found) && is_int($map->found) ? $map->found : 0,
      'hit' => isset($map->hit) && is_int($map->hit) ? $map->hit : 0
    ]);
  }

  /**
   * Gets the coverage data.
   * @return FunctionData[] The coverage data.
   */
  public function getData(): array {
    return $this->data;
  }

  /**
   * Gets the number of functions found.
   * @return int The number of functions found.
   */
  public function getFound(): int {
    return $this->found;
  }

  /**
   * Gets the number of functions hit.
   * @return int The number of functions hit.
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
      'data' => array_map(function(FunctionData $item) { return $item->jsonSerialize(); }, $this->getData()),
      'found' => $this->getFound(),
      'hit' => $this->getHit()
    ];
  }

  /**
   * Sets the coverage data.
   * @param FunctionData[] $value The new coverage data.
   * @return FunctionCoverage This instance.
   */
  public function setData(array $value): self {
    $this->data = $value;
    return $this;
  }

  /**
   * Sets the number of branches found.
   * @param int $value The new number of branches found.
   * @return FunctionCoverage This instance.
   */
  public function setFound(int $value): self {
    $this->found = $value;
    return $this;
  }

  /**
   * Sets the number of branches hit.
   * @param int $value The new number of branches hit.
   * @return FunctionCoverage This instance.
   */
  public function setHit(int $value): self {
    $this->hit = $value;
    return $this;
  }
}
