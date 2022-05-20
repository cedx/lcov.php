<?php declare(strict_types=1);
namespace Lcov;

/**
 * Provides the coverage data of branches.
 */
class BranchCoverage implements \JsonSerializable, \Stringable {

	/**
	 * The coverage data.
	 * @var BranchData[]
	 */
	public array $data;

	/**
	 * The number of branches found.
	 * @var int
	 */
	public int $found;

	/**
	 * The number of branches hit.
	 * @var int
	 */
	public int $hit;

	/**
	 * Creates a new branch coverage.
	 * @param int $found The number of branches found.
	 * @param int $hit The number of branches hit.
	 * @param BranchData[] $data The coverage data.
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
			...array_map(strval(...), $this->data),
			Token::branchesFound->value.":{$this->found}",
			Token::branchesHit->value.":{$this->hit}"
		]);
	}

	/**
	 * Creates a new branch coverage from the specified JSON object.
	 * @param object $map A JSON object representing a branch coverage.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $map): self {
		return new self(
			data: isset($map->data) && is_array($map->data) ? array_map(BranchData::fromJson(...), $map->data) : [],
			found: isset($map->found) && is_int($map->found) ? $map->found : 0,
			hit: isset($map->hit) && is_int($map->hit) ? $map->hit : 0
		);
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"data" => array_map(fn(BranchData $item) => $item->jsonSerialize(), $this->data),
			"found" => $this->found,
			"hit" => $this->hit
		];
	}
}
