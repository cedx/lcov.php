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
	public function __construct(
		public string $path,
		public ?FunctionCoverage $functions = null,
		public ?BranchCoverage $branches = null,
		public ?LineCoverage $lines = null
	) {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	public function __toString(): string {
		$output = [Token::SourceFile->value.":$this->path"];
		if ($this->functions) $output[] = (string) $this->functions;
		if ($this->branches) $output[] = (string) $this->branches;
		if ($this->lines) $output[] = (string) $this->lines;
		$output[] = Token::EndOfRecord->value;
		return implode(PHP_EOL, $output);
	}
}
