<?php namespace Lcov;

/**
 * Provides the coverage data of a source file.
 */
class SourceFile implements \Stringable {

	/**
	 * The branch coverage.
	 * @var BranchCoverage|null
	 */
	public ?BranchCoverage $branches;

	/**
	 * The function coverage.
	 * @var FunctionCoverage|null
	 */
	public ?FunctionCoverage $functions;

	/**
	 * The line coverage.
	 * @var LineCoverage|null
	 */
	public ?LineCoverage $lines;

	/**
	 * The path to the source file.
	 * @var string
	 */
	public string $path;

	/**
	 * Creates a new source file.
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
		$output = [Token::sourceFile->value.":{$this->path}"];
		if ($this->functions) $output[] = (string) $this->functions;
		if ($this->branches) $output[] = (string) $this->branches;
		if ($this->lines) $output[] = (string) $this->lines;
		$output[] = Token::endOfRecord->value;
		return implode(PHP_EOL, $output);
	}

	/**
	 * Creates a new line data from the specified JSON object.
	 * @param object $json A JSON object representing a line data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			branches: isset($json->branches) && is_object($json->branches) ? BranchCoverage::fromJson($json->branches) : null,
			functions: isset($json->functions) && is_object($json->functions) ? FunctionCoverage::fromJson($json->functions) : null,
			lines: isset($json->lines) && is_object($json->lines) ? LineCoverage::fromJson($json->lines) : null,
			path: isset($json->path) && is_string($json->path) ? $json->path : ""
		);
	}
}
