<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides details for branch coverage.
 */
class BranchData implements \Stringable {

	/**
	 * The block number.
	 */
	public int $blockNumber;

	/**
	 * The branch number.
	 */
	public int $branchNumber;

	/**
	 * The line number.
	 */
	public int $lineNumber;

	/**
	 * A number indicating how often this branch was taken.
	 */
	public int $taken;

	/**
	 * Creates new branch data.
	 * @param int $lineNumber The line number.
	 * @param int $blockNumber The block number.
	 * @param int $branchNumber The branch number.
	 * @param int $taken A number indicating how often this branch was taken.
	 */
	public function __construct(int $lineNumber = 0, int $blockNumber = 0, int $branchNumber = 0, int $taken = 0) {
		$this->blockNumber = $blockNumber;
		$this->branchNumber = $branchNumber;
		$this->lineNumber = $lineNumber;
		$this->taken = $taken;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	public function __toString(): string {
		$value = Token::BranchData->value.":$this->lineNumber,$this->blockNumber,$this->branchNumber";
		return $this->taken > 0 ? "$value,$this->taken" : "$value,-";
	}
}
