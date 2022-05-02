<?php declare(strict_types=1);
namespace Lcov;

/**
 * Represents a trace file, that is a coverage report.
 */
class Report implements \JsonSerializable {

	/**
	 * The file list.
	 * @var File[]
	 */
	public array $files;

	/**
	 * The test name.
	 * @var string
	 */
	public string $testName;

	/**
	 * Creates a new report.
	 * @param string $testName The test name.
	 * @param File[] $files The file list.
	 */
	function __construct(string $testName, array $files = []) {
		$this->files = $files;
		$this->testName = $testName;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$token = Token::testName;
		$lines = $this->testName ? ["$token:{$this->testName}"] : [];
		return implode(PHP_EOL, [...$lines, ...array_map("strval", $this->files)]);
	}

	/**
	 * Parses the specified coverage data in LCOV format.
	 * @param string $coverage The coverage data.
	 * @return self The resulting coverage report.
	 * @throws \UnexpectedValueException A parsing error occurred.
	 */
	static function fromString(string $coverage): self {
		$file = new File("");
		$offset = 0;
		$report = new self("");

		foreach (preg_split('/\r?\n/', $coverage) ?: [] as $line) {
			$offset++;
			$line = trim($line);
			if (!mb_strlen($line)) continue;

			$parts = explode(":", $line);
			if (count($parts) < 2 && $parts[0] != Token::endOfRecord) throw new \UnexpectedValueException("Invalid token format at line #$offset.");

			$token = array_shift($parts);
			$data = explode(",", implode(":", $parts));
			$length = count($data);

			switch ($token) {
				case Token::testName: if (!$report->testName) $report->testName = $data[0]; break;
				case Token::endOfRecord: $report->files[] = $file; break;

				case Token::branchData:
					if ($length < 4) throw new \UnexpectedValueException("Invalid branch data at line #$offset.");
					if ($file->branches) $file->branches->data[] = new BranchData(
						blockNumber: (int) $data[1],
						branchNumber: (int) $data[2],
						lineNumber: (int) $data[0],
						taken: $data[3] == "-" ? 0 : (int) $data[3]
					);
					break;

				case Token::functionData:
					if ($length < 2) throw new \UnexpectedValueException("Invalid function data at line #$offset.");
					if ($file->functions) foreach ($file->functions->data as $item) if ($item->functionName == $data[1]) {
						$item->executionCount = (int) $data[0];
						break;
					}
					break;

				case Token::functionName:
					if ($length < 2) throw new \UnexpectedValueException("Invalid function name at line #$offset.");
					if ($file->functions) $file->functions->data[] = new FunctionData(functionName: $data[1], lineNumber: (int) $data[0]);
					break;

				case Token::lineData:
					if ($length < 2) throw new \UnexpectedValueException("Invalid line data at line #$offset.");
					if ($file->lines) $file->lines->data[] = new LineData(
						checksum: $length >= 3 ? $data[2] : "",
						executionCount: (int) $data[1],
						lineNumber: (int) $data[0]
					);
					break;

				case Token::sourceFile:
					$file = new File(
						branches: new BranchCoverage,
						functions: new FunctionCoverage,
						lines: new LineCoverage,
						path: $data[0]
					);
					break;

				case Token::branchesFound: if ($file->branches) $file->branches->found = (int) $data[0]; break;
				case Token::branchesHit: if ($file->branches) $file->branches->hit = (int) $data[0]; break;
				case Token::functionsFound: if ($file->functions) $file->functions->found = (int) $data[0]; break;
				case Token::functionsHit: if ($file->functions) $file->functions->hit = (int) $data[0]; break;
				case Token::linesFound: if ($file->lines) $file->lines->found = (int) $data[0]; break;
				case Token::linesHit: if ($file->lines) $file->lines->hit = (int) $data[0]; break;
				default: throw new \UnexpectedValueException("Unknown token at line #$offset.");
			}
		}

		if (!$report->files) throw new \UnexpectedValueException("The coverage data is empty or invalid.");
		return $report;
	}

	/**
	 * Creates a new line data from the specified JSON object.
	 * @param object $map A JSON object representing a line data.
	 * @return self The instance corresponding to the specified JSON object.
	 */
	static function fromJson(object $map): self {
		return new self(
			isset($map->testName) && is_string($map->testName) ? $map->testName : "",
			isset($map->files) && is_array($map->files) ? array_map([File::class, "fromJson"], $map->files) : []
		);
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"testName" => $this->testName,
			"files" => array_map(fn(File $item) => $item->jsonSerialize(), $this->files)
		];
	}
}
