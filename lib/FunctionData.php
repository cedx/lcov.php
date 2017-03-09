<?php
namespace lcov;

/**
 * Provides details for function coverage.
 */
class FunctionData implements \JsonSerializable {

  /**
   * @var int The execution count.
   */
  private $executionCount;

  /**
   * @var string The function name.
   */
  private $functionName;

  /**
   * @var int The line number of the function start.
   */
  private $lineNumber;

  /**
   * Initializes a new instance of the class.
   * @param string $functionName The function name.
   * @param int $lineNumber The line number of the function start.
   * @param int $executionCount The execution count.
   */
  public function __construct(string $functionName = '', int $lineNumber = 0, int $executionCount = 0) {
    $this->setFunctionName($functionName);
    $this->setLineNumber($lineNumber);
    $this->setExecutionCount($executionCount);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    return $this->toString();
  }

  /**
   * Creates a new function data from the specified JSON map.
   * @param mixed $map A JSON map representing a function data.
   * @return FunctionData The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static(
      isset($map->functionName) && is_string($map->functionName) ? $map->functionName : '',
      isset($map->lineNumber) && is_int($map->lineNumber) ? $map->lineNumber : 0,
      isset($map->executionCount) && is_int($map->executionCount) ? $map->executionCount : 0
    );
  }

  /**
   * Gets the execution count.
   * @return int The execution count.
   */
  public function getExecutionCount(): int {
    return $this->executionCount;
  }

  /**
   * Gets the function name.
   * @return string The function name.
   */
  public function getFunctionName(): string {
    return $this->functionName;
  }

  /**
   * Gets the line number of the function start.
   * @return int The line number of the function start.
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
      'executionCount' => $this->getExecutionCount(),
      'functionName' => $this->getFunctionName(),
      'lineNumber' => $this->getLineNumber()
    ];
  }

  /**
   * Sets the execution count.
   * @param int $value The new execution count.
   * @return FunctionData This instance.
   */
  public function setExecutionCount(int $value): self {
    $this->executionCount = $value;
    return $this;
  }

  /**
   * Sets the function name.
   * @param string $value The new function name.
   * @return FunctionData This instance.
   */
  public function setFunctionName(string $value): self {
    $this->functionName = $value;
    return $this;
  }

  /**
   * Sets the line number of the function start.
   * @param int $value The new line number.
   * @return FunctionData This instance.
   */
  public function setLineNumber(int $value): self {
    $this->lineNumber = $value;
    return $this;
  }

  /**
   * Returns a string representation of this object.
   * @param bool $asDefinition Value indicating whether to return the function definition (e.g. name and line number) instead of its data (e.g. name and execution count).
   * @return string The string representation of this object.
   */
  public function toString(bool $asDefinition = false): string {
    $token = $asDefinition ? Token::FUNCTION_NAME : Token::FUNCTION_DATA;
    $number = $asDefinition ? $this->getLineNumber() : $this->getExecutionCount();
    return "$token:$number,{$this->getFunctionName()}";
  }
}
