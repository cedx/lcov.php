<?php
declare(strict_types=1);
namespace Lcov;

/**
 * Provides the coverage data of a source file.
 */
class Record implements \JsonSerializable {

  /**
   * @var BranchCoverage The branch coverage.
   */
  private $branches;

  /**
   * @var FunctionCoverage The function coverage.
   */
  private $functions;

  /**
   * @var LineCoverage The line coverage.
   */
  private $lines;

  /**
   * @var string The path to the source file.
   */
  private $sourceFile;

  /**
   * Initializes a new instance of the class.
   * @param string $sourceFile The path to the source file.
   */
  public function __construct(string $sourceFile = '') {
    $this->setSourceFile($sourceFile);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $token = Token::SOURCE_FILE;
    $output = ["$token:{$this->getSourceFile()}"];
    if ($functions = $this->getFunctions()) $output[] = (string) $functions;
    if ($branches = $this->getBranches()) $output[] = (string) $branches;
    if ($lines = $this->getLines()) $output[] = (string) $lines;
    $output[] = Token::END_OF_RECORD;
    return implode(PHP_EOL, $output);
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param mixed $map A JSON map representing a line data.
   * @return Record The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJson($map) {
    if (is_array($map)) $map = (object) $map;
    if (!is_object($map)) return null;

    return (new static(isset($map->sourceFile) && is_string($map->sourceFile) ? $map->sourceFile : ''))
      ->setBranches(BranchCoverage::fromJson($map->branches ?? null))
      ->setFunctions(FunctionCoverage::fromJson($map->functions ?? null))
      ->setLines(LineCoverage::fromJson($map->lines ?? null));
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
      'sourceFile' => $this->getSourceFile(),
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
  public function setBranches(BranchCoverage $value = null): self {
    $this->branches = $value;
    return $this;
  }

  /**
   * Sets the function coverage.
   * @param FunctionCoverage $value The new function coverage.
   * @return Record This instance.
   */
  public function setFunctions(FunctionCoverage $value = null): self {
    $this->functions = $value;
    return $this;
  }

  /**
   * Sets the line coverage.
   * @param LineCoverage $value The new line coverage.
   * @return Record This instance.
   */
  public function setLines(LineCoverage $value = null): self {
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
