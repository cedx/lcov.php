<?php declare(strict_types=1);
namespace Lcov;

/**
 * Provides the coverage data of a source file.
 */
class File implements \JsonSerializable {

	/**
	 * The branch coverage.
	 * @var BranchCoverage|null
	 */
	private ?BranchCoverage $branches;

	/**
	 * The function coverage.
	 * @var FunctionCoverage|null
	 */
	private ?FunctionCoverage $functions;

	/**
	 * The line coverage.
	 * @var LineCoverage|null
	 */
	private ?LineCoverage $lines;

	/**
	 * The path to the source file.
	 * @var string
	 */
	private string $path;

	/**
	 * Creates a new file.
	 * @param string $path The path to the source file.
	 * @param FunctionCoverage|null $functions The function coverage.
	 * @param BranchCoverage|null $branches The branch coverage.
	 * @param LineCoverage|null $lines The line coverage.
	 */
	function __construct(string $path, ?FunctionCoverage $functions = null, ?BranchCoverage $branches = null, ?LineCoverage $lines = null) {
		$this->branches = $branches;
		$this->functions = $functions;
		$this->lines = $lines;
		$this->path = $path;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$token = Token::sourceFile;
		$output = ["$token:{$this->path}"];
		if ($this->functions) $output[] = (string) $this->functions;
		if ($this->branches) $output[] = (string) $this->branches;
		if ($this->lines) $output[] = (string) $this->lines;
		$output[] = Token::endOfRecord;
		return implode(PHP_EOL, $output);
	}

	/**
	 * Creates a new line data from the specified JSON object.
	 * @param object $map A JSON object representing a line data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $map): self {
		return new self(
			branches: isset($map->branches) && is_object($map->branches) ? BranchCoverage::fromJson($map->branches) : null,
			functions: isset($map->functions) && is_object($map->functions) ? FunctionCoverage::fromJson($map->functions) : null,
			lines: isset($map->lines) && is_object($map->lines) ? LineCoverage::fromJson($map->lines) : null,
			path: isset($map->path) && is_string($map->path) ? $map->path : ""
		);
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"branches" => $this->branches ? $this->branches->jsonSerialize() : null,
			"functions" => $this->functions ? $this->functions->jsonSerialize() : null,
			"lines" => $this->lines ? $this->lines->jsonSerialize() : null,
			"path" => $this->path
		];
	}
}
