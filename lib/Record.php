<?php declare(strict_types=1);
namespace Lcov;

/** Provides the coverage data of a source file. */
class Record implements \JsonSerializable {

  /** @var BranchCoverage|null The branch coverage. */
  private $branches;

  /** @var FunctionCoverage|null The function coverage. */
  private $functions;

  /** @var LineCoverage|null The line coverage. */
  private $lines;

  /** @var string The path to the source file. */
  private $sourceFile;

  /**
   * Creates a new record.
   * @param string $sourceFile The path to the source file.
   * @param FunctionCoverage $functions The function coverage.
   * @param BranchCoverage $branches The branch coverage.
   * @param LineCoverage $lines The line coverage.
   */
  function __construct(string $sourceFile, FunctionCoverage $functions = null, BranchCoverage $branches = null, LineCoverage $lines = null) {
    $this->sourceFile = $sourceFile;
    $this->setFunctions($functions)->setBranches($branches)->setLines($lines);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  function __toString(): string {
    $token = Token::sourceFile;
    $output = ["$token:{$this->getSourceFile()}"];
    if ($functions = $this->getFunctions()) $output[] = (string) $functions;
    if ($branches = $this->getBranches()) $output[] = (string) $branches;
    if ($lines = $this->getLines()) $output[] = (string) $lines;
    $output[] = Token::endOfRecord;
    return implode(PHP_EOL, $output);
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param object $map A JSON map representing a line data.
   * @return static The instance corresponding to the specified JSON map.
   */
  static function fromJson(object $map): self {
    return new self(
      isset($map->sourceFile) && is_string($map->sourceFile) ? $map->sourceFile : '',
      isset($map->functions) && is_object($map->functions) ? FunctionCoverage::fromJson($map->functions) : null,
      isset($map->branches) && is_object($map->branches) ? BranchCoverage::fromJson($map->branches) : null,
      isset($map->lines) && is_object($map->lines) ? LineCoverage::fromJson($map->lines) : null
    );
  }

  /**
   * Gets the branch coverage.
   * @return BranchCoverage The branch coverage.
   */
  function getBranches(): ?BranchCoverage {
    return $this->branches;
  }

  /**
   * Gets the function coverage.
   * @return FunctionCoverage The function coverage.
   */
  function getFunctions(): ?FunctionCoverage {
    return $this->functions;
  }

  /**
   * Gets the line coverage.
   * @return LineCoverage The line coverage.
   */
  function getLines(): ?LineCoverage {
    return $this->lines;
  }

  /**
   * Gets the path to the source file.
   * @return string The path to the source file.
   */
  function getSourceFile(): string {
    return $this->sourceFile;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  function jsonSerialize(): \stdClass {
    return (object) [
      'sourceFile' => $this->getSourceFile(),
      'branches' => ($branches = $this->getBranches()) ? $branches->jsonSerialize() : null,
      'functions' => ($functions = $this->getFunctions()) ? $functions->jsonSerialize() : null,
      'lines' => ($lines = $this->getLines()) ? $lines->jsonSerialize() : null
    ];
  }

  /**
   * Sets the branch coverage.
   * @param BranchCoverage|null $value The new branch coverage.
   * @return $this This instance.
   */
  function setBranches(?BranchCoverage $value): self {
    $this->branches = $value;
    return $this;
  }

  /**
   * Sets the function coverage.
   * @param FunctionCoverage|null $value The new function coverage.
   * @return $this This instance.
   */
  function setFunctions(?FunctionCoverage $value): self {
    $this->functions = $value;
    return $this;
  }

  /**
   * Sets the line coverage.
   * @param LineCoverage|null $value The new line coverage.
   * @return $this This instance.
   */
  function setLines(?LineCoverage $value): self {
    $this->lines = $value;
    return $this;
  }
}
