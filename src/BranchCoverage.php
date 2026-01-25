<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides the coverage data of branches.
 */
class BranchCoverage implements \Stringable {

	/**
	 * The coverage data.
	 * @var BranchData[]
	 */
	public array $data;

	/**
	 * The number of branches found.
	 */
	public int $found;

	/**
	 * The number of branches hit.
	 */
	public int $hit;

	/**
	 * Creates a new branch coverage.
	 * @param int $found The number of branches found.
	 * @param int $hit The number of branches hit.
	 * @param BranchData[] $data The coverage data.
	 */
	public function __construct(int $found = 0, int $hit = 0, array $data = []) {
		$this->data = $data;
		$this->found = $found;
		$this->hit = $hit;
	}

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
