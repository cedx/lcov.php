<?php
/**
 * Implementation of the `lcov\coverage\Record` class.
 */
namespace lcov\coverage;
use lcov\{Token};

/**
 * Provides the coverage data of a source file.
 */
class Record {

  /**
   * @var BranchCoverage The branch coverage.
   */
  private $branches;

  /**
   * @var FunctionCoverage The function coverage.
   */
  private $functions;

  /**
   * @var int The line coverage.
   */
  private $lines;

  /**
   * @var string The path to the source file.
   */
  private $sourceFile = '';

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    $this->branches = new BranchCoverage();
    $this->functions = new FunctionCoverage();
    $this->lines = new LineCoverage();

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
    $token = Token::SOURCE_FILE;
    $output = ["$token:{$this->getSourceFile()}"];
    if (this.functions) output.push(this.functions.toString());
    if (this.branches) output.push(this.branches.toString());
    if (this.lines) output.push(this.lines.toString());
    $output[] = Token::END_OF_RECORD;
    return output.join('\n');
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param mixed $map A JSON map representing a line data.
   * @return Record The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static([
      'checksum' => isset($map->checksum) && is_string($map->checksum) ? $map->checksum : '',
      'executionCount' => isset($map->hit) && is_int($map->hit) ? $map->hit : 0,
      'lineNumber' => isset($map->line) && is_int($map->line) ? $map->line : 0
    ]);
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
      'hit' => $this->getExecutionCount(),
      'line' => $this->getLineNumber()
    ];
  }

  /**
   * Sets the data checksum.
   * @param string $value The new data checksum.
   * @return Record This instance.
   */
  public function setChecksum(string $value): self {
    $this->checksum = $value;
    return $this;
  }

  /**
   * Sets the execution count.
   * @param int $value The new execution count.
   * @return Record This instance.
   */
  public function setExecutionCount(int $value): self {
    $this->executionCount = $value;
    return $this;
  }

  /**
   * Sets the line number.
   * @param int $value The new line number.
   * @return Record This instance.
   */
  public function setLineNumber(int $value): self {
    $this->lineNumber = $value;
    return $this;
  }
}
