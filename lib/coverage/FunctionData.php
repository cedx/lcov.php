<?php
/**
 * Implementation of the `lcov\coverage\FunctionData` class.
 */
namespace lcov\coverage;
use lcov\{Token};

/**
 * Provides details for line coverage.
 */
class FunctionData {

  /**
   * @var int The execution count.
   */
  private $executionCount = 0;

  /**
   * @var int The line number of the function start.
   */
  private $lineNumber = 0;

  /**
   * @var string The function name.
   */
  private $name = '';

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
   * @param bool $asDefinition Value indicating whether to return the function definition (e.g. name and start line) instead of its data (e.g. name and execution count).
   * @return string The string representation of this object.
   */
  public function __toString(bool $asDefinition = false): string {
    if ($asDefinition) {
      $token = Token::FUNCTION_NAME;
      return "$token:{$this->getLineNumber()},{$this->getName()}";
    }

    $token = Token::FUNCTION_DATA;
    return "$token:{$this->getExecutionCount()},{$this->getName()}";
  }

  /**
   * Creates a new function data from the specified JSON map.
   * @param mixed $map A JSON map representing a function data.
   * @return FunctionData The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static([
      'executionCount' => isset($map->hit) && is_int($map->hit) ? $map->hit : 0,
      'lineNumber' => isset($map->line) && is_int($map->line) ? $map->line : 0,
      'name' => isset($map->name) && is_string($map->name) ? $map->name : ''
    ]);
  }

  /**
   * Gets the execution count.
   * @return int The execution count.
   */
  public function getExecutionCount(): int {
    return $this->executionCount;
  }

  /**
   * Gets the line number of the function start.
   * @return int The line number of the function start.
   */
  public function getLineNumber(): int {
    return $this->lineNumber;
  }

  /**
   * Gets the function name.
   * @return string The function name.
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'hit' => $this->getExecutionCount(),
      'line' => $this->getLineNumber(),
      'name' => $this->getName()
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
   * Sets the line number of the function start.
   * @param int $value The new line number.
   * @return FunctionData This instance.
   */
  public function setLineNumber(int $value): self {
    $this->lineNumber = $value;
    return $this;
  }

  /**
   * Sets the function name.
   * @param string $value The new function name.
   * @return FunctionData This instance.
   */
  public function setName(string $value): self {
    $this->name = $value;
    return $this;
  }
}
