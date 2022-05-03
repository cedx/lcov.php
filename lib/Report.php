<?php declare(strict_types=1);
namespace Lcov;

/**
 * Represents a trace file, that is a coverage report.
 */
class Report implements \JsonSerializable {

	/**
	 * The source file list.
	 * @var SourceFile[]
	 */
	public array $sourceFiles;

	/**
	 * The test name.
	 * @var string
	 */
	public string $testName;

	/**
	 * Creates a new report.
	 * @param string $testName The test name.
	 * @param SourceFile[] $sourceFiles The source file list.
	 */
	function __construct(string $testName, array $sourceFiles = []) {
		$this->sourceFiles = $sourceFiles;
		$this->testName = $testName;
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$lines = $this->testName ? [Token::testName->value.":{$this->testName}"] : [];
		return implode(PHP_EOL, [...$lines, ...array_map(strval(...), $this->sourceFiles)]);
	}

	/**
	 * Parses the specified coverage data in LCOV format.
	 * @param string $coverage The coverage data.
	 * @return self The resulting coverage report.
	 * @throws \UnexpectedValueException A parsing error occurred.
	 */
	static function fromString(string $coverage): self {
		$offset = 0;
		$report = new self("");
		$sourceFile = new SourceFile("");

		foreach (preg_split('/\r?\n/', $coverage) ?: [] as $line) {
			$offset++;
			$line = trim($line);
			if (!mb_strlen($line)) continue;

			$parts = explode(":", $line);
			if (count($parts) < 2 && $parts[0] != Token::endOfRecord->value)
				throw new \UnexpectedValueException("Invalid token format at line #$offset.");

			$token = Token::tryFrom(array_shift($parts));
			$data = explode(",", implode(":", $parts));
			$length = count($data);

			switch ($token) {
				case Token::testName: if (!$report->testName) $report->testName = $data[0]; break;
				case Token::endOfRecord: $report->sourceFiles[] = $sourceFile; break;

				case Token::branchData:
					if ($length < 4) throw new \UnexpectedValueException("Invalid branch data at line #$offset.");
					if ($sourceFile->branches) $sourceFile->branches->data[] = new BranchData(
						blockNumber: (int) $data[1],
						branchNumber: (int) $data[2],
						lineNumber: (int) $data[0],
						taken: $data[3] == "-" ? 0 : (int) $data[3]
					);
					break;

				case Token::functionData:
					if ($length < 2) throw new \UnexpectedValueException("Invalid function data at line #$offset.");
					if ($sourceFile->functions) foreach ($sourceFile->functions->data as $item) if ($item->functionName == $data[1]) {
						$item->executionCount = (int) $data[0];
						break;
					}
					break;

				case Token::functionName:
					if ($length < 2) throw new \UnexpectedValueException("Invalid function name at line #$offset.");
					if ($sourceFile->functions) $sourceFile->functions->data[] = new FunctionData(functionName: $data[1], lineNumber: (int) $data[0]);
					break;

				case Token::lineData:
					if ($length < 2) throw new \UnexpectedValueException("Invalid line data at line #$offset.");
					if ($sourceFile->lines) $sourceFile->lines->data[] = new LineData(
						checksum: $length >= 3 ? $data[2] : "",
						executionCount: (int) $data[1],
						lineNumber: (int) $data[0]
					);
					break;

				case Token::sourceFile:
					$sourceFile = new SourceFile(
						branches: new BranchCoverage,
						functions: new FunctionCoverage,
						lines: new LineCoverage,
						path: $data[0]
					);
					break;

				case Token::branchesFound: if ($sourceFile->branches) $sourceFile->branches->found = (int) $data[0]; break;
				case Token::branchesHit: if ($sourceFile->branches) $sourceFile->branches->hit = (int) $data[0]; break;
				case Token::functionsFound: if ($sourceFile->functions) $sourceFile->functions->found = (int) $data[0]; break;
				case Token::functionsHit: if ($sourceFile->functions) $sourceFile->functions->hit = (int) $data[0]; break;
				case Token::linesFound: if ($sourceFile->lines) $sourceFile->lines->found = (int) $data[0]; break;
				case Token::linesHit: if ($sourceFile->lines) $sourceFile->lines->hit = (int) $data[0]; break;
				default: throw new \UnexpectedValueException("Unknown token at line #$offset.");
			}
		}

		if (!$report->sourceFiles) throw new \UnexpectedValueException("The coverage data is empty or invalid.");
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
			isset($map->sourceFiles) && is_array($map->sourceFiles) ? array_map(SourceFile::fromJson(...), $map->sourceFiles) : []
		);
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"sourceFiles" => array_map(fn(SourceFile $item) => $item->jsonSerialize(), $this->sourceFiles),
			"testName" => $this->testName
		];
	}
}
