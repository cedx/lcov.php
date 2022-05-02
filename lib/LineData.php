<?php declare(strict_types=1);
namespace Lcov;

/**
 * Provides details for line coverage.
 */
class LineData implements \JsonSerializable {

	/**
	 * The data checksum.
	 * @var string
	 */
	public string $checksum;

	/**
	 * The execution count.
	 * @var int
	 */
	public int $executionCount;

	/**
	 * The line number.
	 * @var int
	 */
	public int $lineNumber;

	/**
	 * Creates a new line data.
	 * @param int $lineNumber The line number.
	 * @param int $executionCount The execution count.
	 * @param string $checksum The data checksum.
	 */
	function __construct(int $lineNumber = 0, int $executionCount = 0, string $checksum = "") {
		$this->checksum = $checksum;
		$this->executionCount = $executionCount;
		$this->lineNumber = $lineNumber;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$value = Token::lineData->value.":{$this->lineNumber},{$this->executionCount}";
		return $this->checksum ? "$value,{$this->checksum}" : $value;
	}

	/**
	 * Creates a new line data from the specified JSON object.
	 * @param object $map A JSON object representing a line data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $map): self {
		return new self(
			checksum: isset($map->checksum) && is_string($map->checksum) ? $map->checksum : "",
			executionCount: isset($map->executionCount) && is_int($map->executionCount) ? $map->executionCount : 0,
			lineNumber: isset($map->lineNumber) && is_int($map->lineNumber) ? $map->lineNumber : 0,
		);
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"checksum" => $this->checksum,
			"executionCount" => $this->executionCount,
			"lineNumber" => $this->lineNumber
		];
	}
}
