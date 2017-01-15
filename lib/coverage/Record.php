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
    if ($this->functions) $output[] = (string) $this->functions;
    if ($this->branches) $output[] = (string) $this->branches;
    if ($this->lines) $output[] = (string) $this->lines;
    $output[] = Token::END_OF_RECORD;
    return implode(PHP_EOL, $output);
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param mixed $map A JSON map representing a line data.
   * @return Record The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static([
      'branches' => isset($map->branches) ? BranchCoverage::fromJSON($map->branches) : null,
      'functions' => isset($map->functions) ? FunctionCoverage::fromJSON($map->functions) : null,
      'lines' => isset($map->lines) ? LineCoverage::fromJSON($map->lines) : null,
      'sourceFile' => isset($map->file) && is_string($map->file) ? $map->file : ''
    ]);
  }

  /**
   * Gets the branch coverage.
   * @return BranchCoverage The branch coverage.
   */
  public function getBranches() {
    return $this->branches;
  }

  /**
   * Gets the function coverage.
   * @return FunctionCoverage The function coverage.
   */
  public function getFunctions() {
    return $this->functions;
  }

  /**
   * Gets the line coverage.
   * @return LineCoverage The line coverage.
   */
  public function getLines() {
    return $this->lines;
  }

  /**
   * Gets the path to the source file.
   * @return string The path to the source file.
   */
  public function getSourceFile(): string {
    return $this->sourceFile;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'file' => $this->getSourceFile(),
      'branches' => ($branches = $this->getBranches()) ? $branches->jsonSerialize() : null,
      'functions' => ($functions = $this->getFunctions()) ? $functions->jsonSerialize() : null,
      'lines' => ($lines = $this->getLines()) ? $lines->jsonSerialize() : null
    ];
  }

  /**
   * Sets the branch coverage.
   * @param BranchCoverage $value The new branch coverage.
   * @return Record This instance.
   */
  public function setChecksum(BranchCoverage $value = null): self {
    $this->branches = $value;
    return $this;
  }

  /**
   * Sets the function coverage.
   * @param FunctionCoverage $value The new function coverage.
   * @return Record This instance.
   */
  public function setExecutionCount(FunctionCoverage $value = null): self {
    $this->functions = $value;
    return $this;
  }

  /**
   * Sets the line coverage.
   * @param LineCoverage $value The new line coverage.
   * @return Record This instance.
   */
  public function setLineNumber(LineCoverage $value = null): self {
    $this->lines = $value;
    return $this;
  }

  /**
   * Sets the path to the source file.
   * @param string $value The new path to the source file.
   * @return Record This instance.
   */
  public function setSourceFile(string $value): self {
    $this->sourceFile = $value;
    return $this;
  }
}
