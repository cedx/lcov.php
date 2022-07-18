<?php namespace Lcov;

/**
 * Provides the coverage data of functions.
 */
class FunctionCoverage implements \Stringable {

	/**
	 * The coverage data.
	 * @var FunctionData[]
	 */
	public array $data;

	/**
	 * The number of functions found.
	 * @var int
	 */
	public int $found;

	/**
	 * The number of functions hit.
	 * @var int
	 */
	public int $hit;

	/**
	 * Creates a new function coverage.
	 * @param int $found The number of functions found.
	 * @param int $hit The number of functions hit.
	 * @param FunctionData[] $data The coverage data.
	 */
	function __construct(int $found = 0, int $hit = 0, array $data = []) {
		$this->data = $data;
		$this->found = $found;
		$this->hit = $hit;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		return implode(PHP_EOL, [
			...array_map(fn(FunctionData $item) => $item->toString(true), $this->data),
			...array_map(fn(FunctionData $item) => $item->toString(false), $this->data),
			Token::functionsFound->value.":$this->found",
			Token::functionsHit->value.":$this->hit"
		]);
	}

	/**
	 * Creates a new branch data from the specified JSON object.
	 * @param object $json A JSON object representing a branch data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			data: isset($json->data) && is_array($json->data) ? array_map(FunctionData::fromJson(...), $json->data) : [],
			found: isset($json->found) && is_int($json->found) ? $json->found : 0,
			hit: isset($json->hit) && is_int($json->hit) ? $json->hit : 0
		);
	}
}
