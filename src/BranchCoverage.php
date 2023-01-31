<?php namespace lcov;

/**
 * Provides the coverage data of branches.
 */
class BranchCoverage implements \Stringable {

	/**
	 * Creates a new branch coverage.
	 * @param int $found The number of branches found.
	 * @param int $hit The number of branches hit.
	 * @param BranchData[] $data The coverage data.
	 */
	function __construct(public int $found = 0, public int $hit = 0, public array $data = []) {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		return implode(PHP_EOL, [
			...array_map(strval(...), $this->data),
			Token::branchesFound->value.":$this->found",
			Token::branchesHit->value.":$this->hit"
		]);
	}

	/**
	 * Creates a new branch coverage from the specified JSON object.
	 * @param object $json A JSON object representing a branch coverage.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			data: isset($json->data) && is_array($json->data) ? array_map(BranchData::fromJson(...), $json->data) : [],
			found: isset($json->found) && is_int($json->found) ? $json->found : 0,
			hit: isset($json->hit) && is_int($json->hit) ? $json->hit : 0
		);
	}
}
