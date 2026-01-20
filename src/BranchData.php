<?php declare(strict_types=1);
namespace Belin\Lcov;

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
		$value = Token::BranchData->value.":$this->lineNumber,$this->blockNumber,$this->branchNumber";
		return $this->taken > 0 ? "$value,$this->taken" : "$value,-";
	}

	/**
	 * Creates new branch data from the specified JSON object.
	 * @param object $json A JSON object representing branch data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			blockNumber: (int) ($json->blockNumber ?? 0),
			branchNumber: (int) ($json->branchNumber ?? 0),
			lineNumber: (int) ($json->lineNumber ?? 0),
			taken: (int) ($json->taken ?? 0),
		);
	}
}
