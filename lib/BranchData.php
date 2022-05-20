<?php declare(strict_types=1);
namespace Lcov;

/**
 * Provides details for branch coverage.
 */
class BranchData implements \JsonSerializable, \Stringable {

	/**
	 * The block number.
	 * @var int
	 */
	public int $blockNumber;

	/**
	 * The branch number.
	 * @var int
	 */
	public int $branchNumber;

	/**
	 * The line number.
	 * @var int
	 */
	public int $lineNumber;

	/**
	 * A number indicating how often this branch was taken.
	 * @var int
	 */
	public int $taken;

	/**
	 * Creates a new branch data.
	 * @param int $lineNumber The line number.
	 * @param int $blockNumber The block number.
	 * @param int $branchNumber The branch number.
	 * @param int $taken A number indicating how often this branch was taken.
	 */
	function __construct(int $lineNumber = 0, int $blockNumber = 0, int $branchNumber = 0, int $taken = 0) {
		$this->blockNumber = $blockNumber;
		$this->branchNumber = $branchNumber;
		$this->lineNumber = $lineNumber;
		$this->taken = $taken;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$value = Token::branchData->value.":{$this->lineNumber},{$this->blockNumber},{$this->branchNumber}";
		return $this->taken > 0 ? "$value,{$this->taken}" : "$value,-";
	}

	/**
	 * Creates a new branch data from the specified JSON object.
	 * @param object $map A JSON object representing a branch data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $map): self {
		return new self(
			blockNumber: isset($map->blockNumber) && is_int($map->blockNumber) ? $map->blockNumber : 0,
			branchNumber: isset($map->branchNumber) && is_int($map->branchNumber) ? $map->branchNumber : 0,
			lineNumber: isset($map->lineNumber) && is_int($map->lineNumber) ? $map->lineNumber : 0,
			taken: isset($map->taken) && is_int($map->taken) ? $map->taken : 0,
		);
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"blockNumber" => $this->blockNumber,
			"branchNumber" => $this->branchNumber,
			"lineNumber" => $this->lineNumber,
			"taken" => $this->taken
		];
	}
}
