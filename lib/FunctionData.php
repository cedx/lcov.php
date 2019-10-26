<?php declare(strict_types=1);
namespace Lcov;

/** Provides details for function coverage. */
class FunctionData implements \JsonSerializable {

  /** @var int The execution count. */
  private int $executionCount;

  /** @var string The function name. */
  private string $functionName;

  /** @var int The line number of the function start. */
  private int $lineNumber;

  /**
   * Creates a new function data.
   * @param string $functionName The function name.
   * @param int $lineNumber The line number of the function start.
   * @param int $executionCount The execution count.
   */
  function __construct(string $functionName, int $lineNumber, int $executionCount = 0) {
    $this->functionName = $functionName;
    $this->lineNumber = max(0, $lineNumber);
    $this->setExecutionCount($executionCount);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  function __toString(): string {
    return $this->toString();
  }

  /**
   * Creates a new function data from the specified JSON object.
   * @param object $map A JSON object representing a function data.
   * @return self The instance corresponding to the specified JSON object.
   */
  static function fromJson(object $map): self {
    return new self(
      isset($map->functionName) && is_string($map->functionName) ? $map->functionName : '',
      isset($map->lineNumber) && is_int($map->lineNumber) ? $map->lineNumber : 0,
      isset($map->executionCount) && is_int($map->executionCount) ? $map->executionCount : 0
    );
  }

  /**
   * Gets the execution count.
   * @return int The execution count.
   */
  function getExecutionCount(): int {
    return $this->executionCount;
  }

  /**
   * Gets the function name.
   * @return string The function name.
   */
  function getFunctionName(): string {
    return $this->functionName;
  }

  /**
   * Gets the line number of the function start.
   * @return int The line number of the function start.
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
      'functionName' => $this->getFunctionName(),
      'lineNumber' => $this->getLineNumber(),
      'executionCount' => $this->getExecutionCount()
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

  /**
   * Returns a string representation of this object.
   * @param bool $asDefinition Value indicating whether to return the function definition (e.g. name and line number) instead of its data (e.g. name and execution count).
   * @return string The string representation of this object.
   */
  function toString(bool $asDefinition = false): string {
    $token = $asDefinition ? Token::functionName : Token::functionData;
    $number = $asDefinition ? $this->getLineNumber() : $this->getExecutionCount();
    return "$token:$number,{$this->getFunctionName()}";
  }
}
