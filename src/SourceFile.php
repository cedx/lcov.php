<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides the coverage data of a source file.
 */
class SourceFile implements \Stringable {

	/**
	 * Creates a new source file.
	 * @param string $path The path to the source file.
	 * @param FunctionCoverage|null $functions The function coverage.
	 * @param BranchCoverage|null $branches The branch coverage.
	 * @param LineCoverage|null $lines The line coverage.
	 */
	function __construct(
		public string $path,
		public ?FunctionCoverage $functions = null,
		public ?BranchCoverage $branches = null,
		public ?LineCoverage $lines = null
	) {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$output = [Token::sourceFile->value.":$this->path"];
		if ($this->functions) $output[] = (string) $this->functions;
		if ($this->branches) $output[] = (string) $this->branches;
		if ($this->lines) $output[] = (string) $this->lines;
		$output[] = Token::endOfRecord->value;
		return implode(PHP_EOL, $output);
	}

	/**
	 * Creates a new source file from the specified JSON object.
	 * @param object $json A JSON object representing a source file.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			branches: is_object($branches = $json->branches ?? null) ? BranchCoverage::fromJson($branches) : null,
			functions: is_object($functions = $json->functions ?? null) ? FunctionCoverage::fromJson($functions) : null,
			lines: is_object($lines = $json->lines ?? null) ? LineCoverage::fromJson($lines) : null,
			path: (string) ($json->path ?? "")
		);
	}
}
