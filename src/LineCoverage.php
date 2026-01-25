<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Provides the coverage data of lines.
 */
class LineCoverage implements \Stringable {

	/**
	 * The coverage data.
	 * @var LineData[]
	 */
	public array $data;

	/**
	 * The number of lines found.
	 */
	public int $found;

	/**
	 * The number of lines hit.
	 */
	public int $hit;

	/**
	 * Creates a new line coverage.
	 * @param int $found The number of lines found.
	 * @param int $hit The number of lines hit.
	 * @param LineData[] $data The coverage data.
	 */
	public function __construct(int $found = 0, int $hit = 0, array $data = []) {
		$this->data = $data;
		$this->found = $found;
		$this->hit = $hit;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	public function __toString(): string {
		return implode(PHP_EOL, [
			...array_map(strval(...), $this->data),
			Token::LinesFound->value.":$this->found",
			Token::LinesHit->value.":$this->hit"
		]);
	}
}
