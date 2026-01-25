<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides details for line coverage.
 */
class LineData implements \Stringable {

	/**
	 * The data checksum.
	 */
	public string $checksum;

	/**
	 * The execution count.
	 */
	public int $executionCount;

	/**
	 * The line number.
	 */
	public int $lineNumber;

	/**
	 * Creates new line data.
	 * @param int $lineNumber The line number.
	 * @param int $executionCount The execution count.
	 * @param string $checksum The data checksum.
	 */
	public function __construct(int $lineNumber = 0, int $executionCount = 0, string $checksum = "") {
		$this->checksum = $checksum;
		$this->executionCount = $executionCount;
		$this->lineNumber = $lineNumber;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	public function __toString(): string {
		$value = Token::LineData->value.":$this->lineNumber,$this->executionCount";
		return $this->checksum ? "$value,$this->checksum" : $value;
	}
}
