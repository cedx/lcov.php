<?php namespace lcov;

/**
 * Provides details for branch coverage.
 */
class BranchData implements \Stringable {

	/**
	 * Creates new branch data.
	 * @param int $lineNumber The line number.
	 * @param int $blockNumber The block number.
	 * @param int $branchNumber The branch number.
	 * @param int $taken A number indicating how often this branch was taken.
	 */
	function __construct(public int $lineNumber = 0, public int $blockNumber = 0, public int $branchNumber = 0, public int $taken = 0) {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$value = Token::branchData->value.":$this->lineNumber,$this->blockNumber,$this->branchNumber";
		return $this->taken > 0 ? "$value,$this->taken" : "$value,-";
	}

	/**
	 * Creates new branch data from the specified JSON object.
	 * @param object $json A JSON object representing branch data.
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
