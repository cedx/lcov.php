<?php namespace Lcov;

/**
 * Provides details for function coverage.
 */
class FunctionData implements \Stringable {

	/**
	 * The execution count.
	 * @var int
	 */
	public int $executionCount;

	/**
	 * The function name.
	 * @var string
	 */
	public string $functionName;

	/**
	 * The line number of the function start.
	 * @var int
	 */
	public int $lineNumber;

	/**
	 * Creates a new function data.
	 * @param string $functionName The function name.
	 * @param int $lineNumber The line number of the function start.
	 * @param int $executionCount The execution count.
	 */
	function __construct(string $functionName = "", int $lineNumber = 0, int $executionCount = 0) {
		$this->executionCount = $executionCount;
		$this->functionName = $functionName;
		$this->lineNumber = $lineNumber;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	final function __toString(): string {
		return $this->toString();
	}

	/**
	 * Creates a new function data from the specified JSON object.
	 * @param object $json A JSON object representing a function data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $json): self {
		return new self(
			executionCount: isset($json->executionCount) && is_int($json->executionCount) ? $json->executionCount : 0,
			functionName: isset($json->functionName) && is_string($json->functionName) ? $json->functionName : "",
			lineNumber: isset($json->lineNumber) && is_int($json->lineNumber) ? $json->lineNumber : 0,
		);
	}

	/**
	 * Returns a string representation of this object.
	 * @param bool $asDefinition Value indicating whether to return the function definition (i.e. name and line number) instead of its data (i.e. name and execution count).
	 * @return string The string representation of this object.
	 */
	function toString(bool $asDefinition = false): string {
		$token = $asDefinition ? Token::functionName : Token::functionData;
		$number = $asDefinition ? $this->lineNumber : $this->executionCount;
		return "{$token->value}:$number,{$this->functionName}";
	}
}
