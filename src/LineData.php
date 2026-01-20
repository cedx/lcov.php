<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides details for line coverage.
 */
class LineData implements \Stringable {

	/**
	 * Creates new line data.
	 * @param int $lineNumber The line number.
	 * @param int $executionCount The execution count.
	 * @param string $checksum The data checksum.
	 */
	function __construct(public int $lineNumber = 0, public int $executionCount = 0, public string $checksum = "") {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$value = Token::lineData->value.":$this->lineNumber,$this->executionCount";
		return $this->checksum ? "$value,$this->checksum" : $value;
	}

	/**
	 * Creates new line data from the specified JSON object.
	 * @param object $json A JSON object representing line data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			checksum: (string) ($json->checksum ?? ""),
			executionCount: (int) ($json->executionCount ?? 0),
			lineNumber: (int) ($json->lineNumber ?? 0),
		);
	}
}
