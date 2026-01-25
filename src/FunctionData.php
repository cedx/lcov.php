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
	public final function __toString(): string {
		return implode(PHP_EOL, [$this->toString(true), $this->toString(false)]);
	}

	/**
	 * Returns a string representation of this object.
	 * @param bool $asDefinition Value indicating whether to return the function definition instead of its data.
	 * @return string The string representation of this object.
	 */
	public function toString(bool $asDefinition = false): string {
		$token = $asDefinition ? Token::FunctionName : Token::FunctionData;
		$number = $asDefinition ? $this->lineNumber : $this->executionCount;
		return "{$token->value}:$number,$this->functionName";
	}
}
