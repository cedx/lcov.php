<?php declare(strict_types=1);
namespace Belin\Lcov;

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
	public function __construct(public int $found = 0, public int $hit = 0, public array $data = []) {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	public function __toString(): string {
		return implode(PHP_EOL, [
			...array_map(strval(...), $this->data),
			Token::BranchesFound->value.":$this->found",
			Token::BranchesHit->value.":$this->hit"
		]);
	}
}
