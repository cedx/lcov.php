<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides details for function coverage.
 */
class FunctionData implements \Stringable {

	/**
	 * The execution count.
	 */
	public int $executionCount;

	/**
	 * The function name.
	 */
	public string $functionName;

	/**
	 * The line number of the function start.
	 */
	public int $lineNumber;

	/**
	 * Creates new function data.
	 * @param string $functionName The function name.
	 * @param int $lineNumber The line number of the function start.
	 * @param int $executionCount The execution count.
	 */
	public function __construct(string $functionName = "", int $lineNumber = 0, int $executionCount = 0) {
		$this->executionCount = $executionCount;
		$this->functionName = $functionName;
		$this->lineNumber = $lineNumber;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	public function __toString(): string {
		return implode("\n", [
			Token::FunctionName->value . ":$this->lineNumber,$this->functionName",
			Token::FunctionData->value . ":$this->executionCount,$this->functionName"
		]);
	}
}
