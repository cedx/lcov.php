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
	public function __construct(public int $found = 0, public int $hit = 0, public array $data = []) {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	public function __toString(): string {
		return implode(PHP_EOL, [
			...array_map(fn(FunctionData $item) => $item->toString(asDefinition: true), $this->data),
			...array_map(fn(FunctionData $item) => $item->toString(asDefinition: false), $this->data),
			Token::FunctionsFound->value.":$this->found",
			Token::FunctionsHit->value.":$this->hit"
		]);
	}
}
