<?php
/**
 * Implementation of the `lcov\coverage\LineData` class.
 */
namespace lcov\coverage;
use lcov\{Token};

/**
 * Provides details for branch coverage.
 */
class LineData {

  /**
   * @var int The data checksum.
   */
  private $checksum = 0;

  /**
   * @var int The execution count.
   */
  private $executionCount = 0;

  /**
   * @var int The line number.
   */
  private $lineNumber = 0;

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
    $value = "$token:{$this->getLineNumber()},{$this->getExecutionCount()},{$this->getChecksum()}";
    return ($taken = $this->getTaken()) > 0 ? "$value,$taken" : "$value,-";
  }

  /**
   * Creates a new branch data from the specified JSON map.
   * @param mixed $map A JSON map representing a branch data.
   * @return LineData The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static([
      'checksum' => isset($map->branch) && is_int($map->branch) ? $map->branch : '',
      'executionCount' => isset($map->block) && is_int($map->block) ? $map->block : 0,
      'lineNumber' => isset($map->line) && is_int($map->line) ? $map->line : 0
    ]);
  }

  /**
   * Gets the branch number.
   * @return int The branch number.
   */
  public function getChecksum(): int {
    return $this->checksum;
  }

  /**
   * Gets the block number.
   * @return int The block number.
   */
  public function getExecutionCount(): int {
    return $this->executionCount;
  }

  /**
   * Gets the line number.
   * @return int The line number.
   */
  public function getLineNumber(): int {
    return $this->lineNumber;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'branch' => $this->getChecksum(),
      'block' => $this->getExecutionCount(),
      'line' => $this->getLineNumber()
    ];
  }

  /**
   * Sets the branch number.
   * @param int $value The new branch number.
   * @return LineData This instance.
   */
  public function setChecksum(int $value): self {
    $this->checksum = $value;
    return $this;
  }

  /**
   * Sets the block number.
   * @param int $value The new block number.
   * @return LineData This instance.
   */
  public function setExecutionCount(int $value): self {
    $this->executionCount = $value;
    return $this;
  }

  /**
   * Sets the line number.
   * @param int $value The new line number.
   * @return LineData This instance.
   */
  public function setLineNumber(int $value): self {
    $this->lineNumber = $value;
    return $this;
  }
}
