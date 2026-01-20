<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides the coverage data of functions.
 */
class FunctionCoverage implements \Stringable {

	/**
	 * Creates a new function coverage.
	 * @param int $found The number of functions found.
	 * @param int $hit The number of functions hit.
	 * @param FunctionData[] $data The coverage data.
	 */
	function __construct(public int $found = 0, public int $hit = 0, public array $data = []) {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		return implode(PHP_EOL, [
			...array_map(fn(FunctionData $item) => $item->toString(asDefinition: true), $this->data),
			...array_map(fn(FunctionData $item) => $item->toString(asDefinition: false), $this->data),
			Token::functionsFound->value.":$this->found",
			Token::functionsHit->value.":$this->hit"
		]);
	}

	/**
	 * Creates a new function coverage from the specified JSON object.
	 * @param object $json A JSON object representing a function coverage.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			data: array_map(FunctionData::fromJson(...), (array) ($json->data ?? [])),
			found: (int) ($json->found ?? 0),
			hit: (int) ($json->hit ?? 0)
		);
	}
}
