<?php namespace Lcov;

/**
 * Provides the coverage data of functions.
 */
class FunctionCoverage implements \JsonSerializable, \Stringable {

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
			Token::functionsFound->value.":{$this->found}",
			Token::functionsHit->value.":{$this->hit}"
		]);
	}

	/**
	 * Creates a new branch data from the specified JSON object.
	 * @param \stdClass $map A JSON object representing a branch data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(\stdClass $map): self {
		return new self(
			data: is_array($map->data) ? array_map(FunctionData::fromJson(...), $map->data) : [],
			found: is_int($map->found) ? $map->found : 0,
			hit: is_int($map->hit) ? $map->hit : 0
		);
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"data" => array_map(fn(FunctionData $item) => $item->jsonSerialize(), $this->data),
			"found" => $this->found,
			"hit" => $this->hit
		];
	}
}
