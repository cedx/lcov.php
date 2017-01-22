<?php
/**
 * Implementation of the `lcov\LineData` class.
 */
namespace lcov;

/**
 * Provides details for line coverage.
 */
class LineData {

  /**
   * @var string The data checksum.
   */
  private $checksum;

  /**
   * @var int The execution count.
   */
  private $executionCount;

  /**
   * @var int The line number.
   */
  private $lineNumber;

  /**
   * Initializes a new instance of the class.
   * @param int $lineNumber The line number.
   * @param int $executionCount The execution count.
   * @param string $checksum The data checksum.
   */
  public function __construct(int $lineNumber = 0, int $executionCount = 0, string $checksum = '') {
    $this->setLineNumber($lineNumber);
    $this->setExecutionCount($executionCount);
    $this->setChecksum($checksum);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $token = Token::LINE_DATA;
    $value = "$token:{$this->getLineNumber()},{$this->getExecutionCount()}";
    return mb_strlen($checksum = $this->getChecksum()) ? "$value,$checksum" : $value;
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param mixed $map A JSON map representing a line data.
   * @return LineData The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static(
      isset($map->lineNumber) && is_int($map->lineNumber) ? $map->lineNumber : 0,
      isset($map->executionCount) && is_int($map->executionCount) ? $map->executionCount : 0,
      isset($map->checksum) && is_string($map->checksum) ? $map->checksum : ''
    );
  }

  /**
   * Gets the data checksum.
   * @return string The data checksum.
   */
  public function getChecksum(): string {
    return $this->checksum;
  }

  /**
   * Gets the execution count.
   * @return int The execution count.
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
      'checksum' => $this->getChecksum(),
      'executionCount' => $this->getExecutionCount(),
      'lineNumber' => $this->getLineNumber()
    ];
  }

  /**
   * Sets the data checksum.
   * @param string $value The new data checksum.
   * @return LineData This instance.
   */
  public function setChecksum(string $value): self {
    $this->checksum = $value;
    return $this;
  }

  /**
   * Sets the execution count.
   * @param int $value The new execution count.
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