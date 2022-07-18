<?php namespace Lcov;

/**
 * Provides details for branch coverage.
 */
class BranchData implements \Stringable {

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
		$value = Token::branchData->value.":$this->lineNumber,$this->blockNumber,$this->branchNumber";
		return $this->taken > 0 ? "$value,$this->taken" : "$value,-";
	}

	/**
	 * Creates a new branch data from the specified JSON object.
	 * @param object $json A JSON object representing a branch data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			blockNumber: isset($json->blockNumber) && is_int($json->blockNumber) ? $json->blockNumber : 0,
			branchNumber: isset($json->branchNumber) && is_int($json->branchNumber) ? $json->branchNumber : 0,
			lineNumber: isset($json->lineNumber) && is_int($json->lineNumber) ? $json->lineNumber : 0,
			taken: isset($json->taken) && is_int($json->taken) ? $json->taken : 0,
		);
	}
}
