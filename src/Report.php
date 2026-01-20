<?php declare(strict_types=1);
namespace Belin\Lcov;

/**
 * Represents a trace file, that is a coverage report.
 */
class Report implements \Stringable {

	/**
	 * Creates a new report.
	 * @param string $testName The test name.
	 * @param SourceFile[] $sourceFiles The source file list.
	 */
	function __construct(public string $testName, public array $sourceFiles = []) {}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$lines = $this->testName ? [Token::TestName->value.":$this->testName"] : [];
		return implode(PHP_EOL, [...$lines, ...array_map(strval(...), $this->sourceFiles)]);
	}

	/**
	 * Parses the specified coverage data in LCOV format.
	 * @param string $coverage The coverage data.
	 * @return self The resulting coverage report.
	 * @throws \InvalidArgumentException A parsing error occurred.
	 */
	static function parse(string $coverage): self {
		$offset = 0;
		$report = new self("");
		$sourceFile = new SourceFile("");

		foreach (preg_split('/\r?\n/', $coverage) ?: [] as $line) {
			$offset++;
			if (!mb_strlen($line = mb_trim($line))) continue;

			$parts = explode(":", $line);
			$token = Token::tryFrom(array_shift($parts));
			$data = implode(":", $parts) |> (fn($value) => explode(",", $value));

			switch ($token) {
				case Token::TestName: if (!$report->testName) $report->testName = $data[0]; break;
				case Token::EndOfRecord: $report->sourceFiles[] = $sourceFile; break;

				case Token::BranchData:
					if (count($data) < 4) throw new \InvalidArgumentException("Invalid branch data at line #$offset.", 422);
					if ($sourceFile->branches) $sourceFile->branches->data[] = new BranchData(
						blockNumber: (int) $data[1],
						branchNumber: (int) $data[2],
						lineNumber: (int) $data[0],
						taken: $data[3] == "-" ? 0 : (int) $data[3]
					);
					break;

				case Token::FunctionData:
					if (count($data) < 2) throw new \InvalidArgumentException("Invalid function data at line #$offset.", 422);
					if ($sourceFile->functions) foreach ($sourceFile->functions->data as $item) if ($item->functionName == $data[1]) {
						$item->executionCount = (int) $data[0];
						break;
					}
					break;

				case Token::FunctionName:
					if (count($data) < 2) throw new \InvalidArgumentException("Invalid function name at line #$offset.", 422);
					if ($sourceFile->functions) $sourceFile->functions->data[] = new FunctionData(functionName: $data[1], lineNumber: (int) $data[0]);
					break;

				case Token::LineData:
					if (($length = count($data)) < 2) throw new \InvalidArgumentException("Invalid line data at line #$offset.", 422);
					if ($sourceFile->lines) $sourceFile->lines->data[] = new LineData(
						checksum: $length >= 3 ? $data[2] : "",
						executionCount: (int) $data[1],
						lineNumber: (int) $data[0]
					);
					break;

				case Token::SourceFile:
					$sourceFile = new SourceFile(
						branches: new BranchCoverage,
						functions: new FunctionCoverage,
						lines: new LineCoverage,
						path: $data[0]
					);
					break;

				case Token::BranchesFound: if ($sourceFile->branches) $sourceFile->branches->found = (int) $data[0]; break;
				case Token::BranchesHit: if ($sourceFile->branches) $sourceFile->branches->hit = (int) $data[0]; break;
				case Token::FunctionsFound: if ($sourceFile->functions) $sourceFile->functions->found = (int) $data[0]; break;
				case Token::FunctionsHit: if ($sourceFile->functions) $sourceFile->functions->hit = (int) $data[0]; break;
				case Token::LinesFound: if ($sourceFile->lines) $sourceFile->lines->found = (int) $data[0]; break;
				case Token::LinesHit: if ($sourceFile->lines) $sourceFile->lines->hit = (int) $data[0]; break;
				default: throw new \InvalidArgumentException("Unknown token at line #$offset.", 422);
			}
		}

		if (!$report->sourceFiles) throw new \InvalidArgumentException("The coverage data is empty or invalid.", 400);
		return $report;
	}
	/**
	 * Parses the specified coverage data in LCOV format.
	 * @param string $coverage The coverage data.
	 * @return self|null The resulting coverage report, or `null` if an error occurred.
	 */
	static function tryParse(string $coverage): ?self {
		try { return self::parse($coverage); }
		catch (\InvalidArgumentException) { return null; }
	}
}
