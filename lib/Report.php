<?php declare(strict_types=1);
namespace Lcov;

/** Provides the coverage data of a source file. */
class Report implements \JsonSerializable {

	/** @var \ArrayObject<int, Record> The record list. */
	private \ArrayObject $records;

	/** @var string The test name. */
	private string $testName;

	/**
	 * Creates a new report.
	 * @param string $testName The test name.
	 * @param Record[] $records The record list.
	 */
	function __construct(string $testName = "", array $records = []) {
		$this->records = new \ArrayObject($records);
		$this->setTestName($testName);
	}

	/**
	 * Returns a string representation of this object.
	 * @return string The string representation of this object.
	 */
	function __toString(): string {
		$token = Token::testName;
		$lines = mb_strlen($testName = $this->getTestName()) ? ["$token:$testName"] : [];
		return implode(PHP_EOL, [...$lines, ...array_map("strval", (array) $this->getRecords())]);
	}

	/**
	 * Parses the specified coverage data in LCOV format.
	 * @param string $coverage The coverage data.
	 * @return self The resulting coverage report.
	 * @throws \UnexpectedValueException A parsing error occurred.
	 */
	static function fromCoverage(string $coverage): self {
		$report = new self;
		$records = $report->getRecords();

		try {
			/** @var Record $record */
			$record = null;
			foreach (preg_split('/\r?\n/', $coverage) ?: [] as $line) {
				$line = trim($line);
				if (!mb_strlen($line)) continue;

				$parts = explode(":", $line);
				if (count($parts) < 2 && $parts[0] != Token::endOfRecord) throw new \DomainException("Invalid token format");

				$token = array_shift($parts);
				$data = explode(",", implode(":", $parts));
				$length = count($data);

				switch ($token) {
					case Token::testName:
						$report->setTestName($data[0]);
						break;

					case Token::sourceFile:
						$record = new Record($data[0], new FunctionCoverage, new BranchCoverage, new LineCoverage);
						break;

					case Token::functionName:
						if ($length < 2) throw new \DomainException("Invalid function name");
						if ($functions = $record->getFunctions()) $functions->getData()->append(new FunctionData($data[1], (int) $data[0]));
						break;

					case Token::functionData:
						if ($length < 2) throw new \DomainException("Invalid function data");
						if ($functions = $record->getFunctions()) foreach ($functions->getData() as $item) {
							/** @var FunctionData $item */
							if ($item->getFunctionName() == $data[1]) {
								$item->setExecutionCount((int) $data[0]);
								break;
							}
						}
						break;

					case Token::functionsFound:
						if ($functions = $record->getFunctions()) $functions->setFound((int) $data[0]);
						break;

					case Token::functionsHit:
						if ($functions = $record->getFunctions()) $functions->setHit((int) $data[0]);
						break;

					case Token::branchData:
						if ($length < 4) throw new \DomainException("Invalid branch data");
						if ($branches = $record->getBranches()) $branches->getData()->append(new BranchData(
							(int) $data[0],
							(int) $data[1],
							(int) $data[2],
							$data[3] == "-" ? 0 : (int) $data[3]
						));
						break;

					case Token::branchesFound:
						if ($branches = $record->getBranches()) $branches->setFound((int) $data[0]);
						break;

					case Token::branchesHit:
						if ($branches = $record->getBranches()) $branches->setHit((int) $data[0]);
						break;

					case Token::lineData:
						if ($length < 2) throw new \DomainException("Invalid line data");
						if ($lines = $record->getLines()) $lines->getData()->append(new LineData(
							(int) $data[0],
							(int) $data[1],
							$length >= 3 ? $data[2] : ""
						));
						break;

					case Token::linesFound:
						if ($lines = $record->getLines()) $lines->setFound((int) $data[0]);
						break;

					case Token::linesHit:
						if ($lines = $record->getLines()) $lines->setHit((int) $data[0]);
						break;

					case Token::endOfRecord:
						$records->append($record);
						break;

					default:
						throw new \DomainException("Unknown token");
				}
			}
		}

		catch (\Throwable $e) {
			throw new LcovException("The coverage data has an invalid LCOV format", $coverage, 0, $e);
		}

		if (!count($records)) throw new LcovException("The coverage data is empty", $coverage);
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
			isset($map->records) && is_array($map->records) ? array_map([Record::class, "fromJson"], $map->records) : []
		);
	}

	/**
	 * Gets the record list.
	 * @return \ArrayObject<int, Record> The record list.
	 */
	function getRecords(): \ArrayObject {
		return $this->records;
	}

	/**
	 * Gets the test name.
	 * @return string The test name.
	 */
	function getTestName(): string {
		return $this->testName;
	}

	/**
	 * Converts this object to a map in JSON format.
	 * @return \stdClass The map in JSON format corresponding to this object.
	 */
	function jsonSerialize(): \stdClass {
		return (object) [
			"testName" => $this->getTestName(),
			"records" => array_map(fn(Record $item) => $item->jsonSerialize(), (array) $this->getRecords())
		];
	}

	/**
	 * Sets the test name.
	 * @param string $value The new test name.
	 * @return $this This instance.
	 */
	function setTestName(string $value): self {
		$this->testName = $value;
		return $this;
	}
}
