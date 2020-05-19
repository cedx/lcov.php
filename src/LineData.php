<?php declare(strict_types=1);
namespace Lcov;

/** Provides details for line coverage.*/
class LineData implements \JsonSerializable {

	/** @var string The data checksum. */
	private string $checksum;

	/** @var int The execution count. */
	private int $executionCount;

	/** @var int The line number. */
	private int $lineNumber;

	/**
	 * Creates a new line data.
	 * @param int $lineNumber The line number.
	 * @param int $executionCount The execution count.
	 * @param string $checksum The data checksum.
	 */
	function __construct(int $lineNumber, int $executionCount = 0, string $checksum = "") {
		assert($lineNumber >= 0);
		$this->checksum = $checksum;
		$this->lineNumber = $lineNumber;
		$this->setExecutionCount($executionCount);
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$token = Token::lineData;
		$value = "$token:{$this->getLineNumber()},{$this->getExecutionCount()}";
		return mb_strlen($checksum = $this->getChecksum()) ? "$value,$checksum" : $value;
	}

	/**
	 * Creates a new line data from the specified JSON object.
	 * @param object $map A JSON object representing a line data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $map): self {
		return new self(
			isset($map->lineNumber) && is_int($map->lineNumber) ? $map->lineNumber : 0,
			isset($map->executionCount) && is_int($map->executionCount) ? $map->executionCount : 0,
			isset($map->checksum) && is_string($map->checksum) ? $map->checksum : ""
		);
	}

	/**
	 * Gets the data checksum.
	 * @return string The data checksum.
	 */
	function getChecksum(): string {
		return $this->checksum;
	}

	/**
	 * Gets the execution count.
	 * @return int The execution count.
	 */
	function getExecutionCount(): int {
		return $this->executionCount;
	}

	/**
	 * Gets the line number.
	 * @return int The line number.
	 */
	function getLineNumber(): int {
		return $this->lineNumber;
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"lineNumber" => $this->getLineNumber(),
			"executionCount" => $this->getExecutionCount(),
			"checksum" => $this->getChecksum()
		];
	}

	/**
	 * Sets the execution count.
	 * @param int $value The new execution count.
	 * @return $this This instance.
	 */
	function setExecutionCount(int $value): self {
		assert($value >= 0);
		$this->executionCount = $value;
		return $this;
	}
}
