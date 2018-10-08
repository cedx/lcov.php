<?php
declare(strict_types=1);
namespace Lcov;

/**
 * Provides the coverage data of a source file.
 */
class Report implements \JsonSerializable {

  /**
   * @var \ArrayObject The record list.
   */
  private $records;

  /**
   * @var string The test name.
   */
  private $testName;

  /**
   * Creates a new report.
   * @param string $testName The test name.
   * @param Record[] $records The record list.
   */
  function __construct(string $testName = '', array $records = []) {
    $this->records = new \ArrayObject($records);
    $this->setTestName($testName);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  function __toString(): string {
    $token = Token::TEST_NAME;
    $lines = mb_strlen($testName = $this->getTestName()) ? ["$token:$testName"] : [];
    $lines = array_merge($lines, array_map('strval', $this->getRecords()->getArrayCopy()));
    return implode(PHP_EOL, $lines);
  }

  /**
   * Parses the specified coverage data in LCOV format.
   * @param string $coverage The coverage data.
   * @return static The resulting coverage report.
   * @throws \UnexpectedValueException A parsing error occurred.
   */
  static function fromCoverage(string $coverage): self {
    $report = new static;
    $records = $report->getRecords();

    try {
      /** @var Record $record */
      $record = null;
      foreach (preg_split('/\r?\n/', $coverage) ?: [] as $line) {
        $line = trim($line);
        if (!mb_strlen($line)) continue;

        $parts = explode(':', $line);
        if (count($parts) < 2 && $parts[0] != Token::END_OF_RECORD) throw new \DomainException('Invalid token format');

        $token = array_shift($parts);
        $data = explode(',', implode(':', $parts));
        $length = count($data);

        switch ($token) {
          case Token::TEST_NAME:
            $report->setTestName($data[0]);
            break;

          case Token::SOURCE_FILE:
            $record = new Record($data[0], new FunctionCoverage, new BranchCoverage, new LineCoverage);
            break;

          case Token::FUNCTION_NAME:
            if ($length < 2) throw new \DomainException('Invalid function name');
            if ($functions = $record->getFunctions()) $functions->getData()->append(new FunctionData($data[1], (int) $data[0]));
            break;

          case Token::FUNCTION_DATA:
            if ($length < 2) throw new \DomainException('Invalid function data');
            if ($functions = $record->getFunctions()) foreach ($functions->getData() as $item) {
              if ($item->getFunctionName() == $data[1]) {
                $item->setExecutionCount((int) $data[0]);
                break;
              }
            }
            break;

          case Token::FUNCTIONS_FOUND:
            if ($functions = $record->getFunctions()) $functions->setFound((int) $data[0]);
            break;

          case Token::FUNCTIONS_HIT:
            if ($functions = $record->getFunctions()) $functions->setHit((int) $data[0]);
            break;

          case Token::BRANCH_DATA:
            if ($length < 4) throw new \DomainException('Invalid branch data');
            if ($branches = $record->getBranches()) $branches->getData()->append(new BranchData(
              (int) $data[0],
              (int) $data[1],
              (int) $data[2],
              $data[3] == '-' ? 0 : (int) $data[3]
            ));
            break;

          case Token::BRANCHES_FOUND:
            if ($branches = $record->getBranches()) $branches->setFound((int) $data[0]);
            break;

          case Token::BRANCHES_HIT:
            if ($branches = $record->getBranches()) $branches->setHit((int) $data[0]);
            break;

          case Token::LINE_DATA:
            if ($length < 2) throw new \DomainException('Invalid line data');
            if ($lines = $record->getLines()) $lines->getData()->append(new LineData(
              (int) $data[0],
              (int) $data[1],
              $length >= 3 ? $data[2] : ''
            ));
            break;

          case Token::LINES_FOUND:
            if ($lines = $record->getLines()) $lines->setFound((int) $data[0]);
            break;

          case Token::LINES_HIT:
            if ($lines = $record->getLines()) $lines->setHit((int) $data[0]);
            break;

          case Token::END_OF_RECORD:
            $records->append($record);
            break;
        }
      }
    }

    catch (\Throwable $e) {
      throw new LcovException('The coverage data has an invalid LCOV format', $coverage, -1, $e);
    }

    if (!count($records)) throw new LcovException('The coverage data is empty', $coverage);
    return $report;
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param object $map A JSON map representing a line data.
   * @return static The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  static function fromJson(object $map): self {
    $transform = function(array $records) {
      return array_map([Record::class, 'fromJson'], $records);
    };

    return new static(
      isset($map->testName) && is_string($map->testName) ? $map->testName : '',
      isset($map->records) && is_array($map->records) ? $transform($map->records) : []
    );
  }

  /**
   * Gets the record list.
   * @return \ArrayObject The record list.
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
      'testName' => $this->getTestName(),
      'records' => array_map(function(Record $item) {
        return $item->jsonSerialize();
      }, $this->getRecords()->getArrayCopy())
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
