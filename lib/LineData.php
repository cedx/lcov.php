<?php declare(strict_types=1);
namespace Lcov;

/** Provides details for line coverage.*/
class LineData implements \JsonSerializable {

  /** @var string The data checksum. */
  private $checksum;

  /** @var int The execution count. */
  private $executionCount;

  /** @var int The line number. */
  private $lineNumber;

  /**
   * Creates a new line data.
   * @param int $lineNumber The line number.
   * @param int $executionCount The execution count.
   * @param string $checksum The data checksum.
   */
  function __construct(int $lineNumber, int $executionCount = 0, string $checksum = '') {
    $this->lineNumber = max(0, $lineNumber);
    $this->setExecutionCount($executionCount);
    $this->checksum = $checksum;
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  function __toString(): string {
    $token = Token::lineData;
    $value = "$token:{$this->getLineNumber()},{$this->getExecutionCount()}";
    return mb_strlen($checksum = $this->getChecksum()) ? "$value,$checksum" : $value;
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param object $map A JSON map representing a line data.
   * @return static The instance corresponding to the specified JSON map.
   */
  static function fromJson(object $map): self {
    return new static(
      isset($map->lineNumber) && is_int($map->lineNumber) ? $map->lineNumber : 0,
      isset($map->executionCount) && is_int($map->executionCount) ? $map->executionCount : 0,
      isset($map->checksum) && is_string($map->checksum) ? $map->checksum : ''
    );
  }

  /**
   * Gets the data checksum.
   * @return string The data checksum.
   */
  function getChecksum(): string {
    return $this->checksum;
  }

  /**
   * Gets the execution count.
   * @return int The execution count.
   */
  function getExecutionCount(): int {
    return $this->executionCount;
  }

  /**
   * Gets the line number.
   * @return int The line number.
   */
  function getLineNumber(): int {
    return $this->lineNumber;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  function jsonSerialize(): \stdClass {
    return (object) [
      'lineNumber' => $this->getLineNumber(),
      'executionCount' => $this->getExecutionCount(),
      'checksum' => $this->getChecksum()
    ];
  }

  /**
   * Sets the execution count.
   * @param int $value The new execution count.
   * @return $this This instance.
   */
  function setExecutionCount(int $value): self {
    $this->executionCount = max(0, $value);
    return $this;
  }
}
