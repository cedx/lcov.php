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
   * Initializes a new instance of the class.
   * @param string $testName The test name.
   * @param Record[] $records The record list.
   */
  public function __construct(string $testName = '', array $records = []) {
    $this->records = new \ArrayObject($records);
    $this->setTestName($testName);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $token = Token::TEST_NAME;
    $lines = mb_strlen($testName = $this->getTestName()) ? ["$token:$testName"] : [];
    $lines = array_merge($lines, array_map('strval', $this->getRecords()->getArrayCopy()));
    return implode(PHP_EOL, $lines);
  }

  /**
   * Parses the specified coverage data in [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) format.
   * @param string $coverage The coverage data.
   * @return Report The resulting coverage report.
   * @throws \UnexpectedValueException A parsing error occurred.
   */
  public static function fromCoverage(string $coverage): self {
    $report = new static;
    $records = $report->getRecords();

    try {
      $record = null;
      foreach (preg_split('/\r?\n/', $coverage) as $line) {
        $line = trim($line);
        if (!mb_strlen($line)) continue;

        $parts = explode(':', $line);
        if (count($parts) < 2 && $parts[0] != Token::END_OF_RECORD) throw new \DomainException('Invalid token format.');

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
            if ($length < 2) throw new \DomainException('Invalid function name.');
            $record->getFunctions()->getData()->append(new FunctionData($data[1], (int) $data[0]));
            break;

          case Token::FUNCTION_DATA:
            if ($length < 2) throw new \DomainException('Invalid function data.');
            foreach ($record->getFunctions()->getData() as $item) {
              if ($item->getFunctionName() == $data[1]) {
                $item->setExecutionCount((int) $data[0]);
                break;
              }
            }
            break;

          case Token::FUNCTIONS_FOUND:
            $record->getFunctions()->setFound((int) $data[0]);
            break;

          case Token::FUNCTIONS_HIT:
            $record->getFunctions()->setHit((int) $data[0]);
            break;

          case Token::BRANCH_DATA:
            if ($length < 4) throw new \DomainException('Invalid branch data.');
            $record->getBranches()->getData()->append(new BranchData(
              (int) $data[0],
              (int) $data[1],
              (int) $data[2],
              $data[3] == '-' ? 0 : (int) $data[3]
            ));
            break;

          case Token::BRANCHES_FOUND:
            $record->getBranches()->setFound((int) $data[0]);
            break;

          case Token::BRANCHES_HIT:
            $record->getBranches()->setHit((int) $data[0]);
            break;

          case Token::LINE_DATA:
            if ($length < 2) throw new \DomainException('Invalid line data.');
            $record->getLines()->getData()->append(new LineData(
              (int) $data[0],
              (int) $data[1],
              $length >= 3 ? $data[2] : ''
            ));
            break;

          case Token::LINES_FOUND:
            $record->getLines()->setFound((int) $data[0]);
            break;

          case Token::LINES_HIT:
            $record->getLines()->setHit((int) $data[0]);
            break;

          case Token::END_OF_RECORD:
            $records->append($record);
            break;
        }
      }
    }

    catch (\Throwable $e) {
      throw new \UnexpectedValueException('The coverage data has an invalid LCOV format.');
    }

    if (!count($records)) throw new \UnexpectedValueException('The coverage data is empty.');
    return $report;
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param mixed $map A JSON map representing a line data.
   * @return Report The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJson($map): ?self {
    $transform = function(array $records) {
      return array_values(array_filter(array_map([Record::class, 'fromJson'], $records)));
    };

    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static(
      isset($map->testName) && is_string($map->testName) ? $map->testName : '',
      isset($map->records) && is_array($map->records) ? $transform($map->records) : []
    );
  }

  /**
   * Gets the record list.
   * @return \ArrayObject The record list.
   */
  public function getRecords(): \ArrayObject {
    return $this->records;
  }

  /**
   * Gets the test name.
   * @return string The test name.
   */
  public function getTestName(): string {
    return $this->testName;
  }

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
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
   * @return Report This instance.
   */
  public function setTestName(string $value): self {
    $this->testName = $value;
    return $this;
  }
}
