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
}
