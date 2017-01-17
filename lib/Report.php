<?php
/**
 * Implementation of the `lcov\Report` class.
 */
namespace lcov;

/**
 * Provides the coverage data of a source file.
 */
class Report {

  /**
   * @var \ArrayObject The record list.
   */
  private $records;

  /**
   * @var string The test name.
   */
  private $testName = '';

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    $this->records = new \ArrayObject();

    foreach ($config as $property => $value) {
      $setter = "set$property";
      if (method_exists($this, $setter)) $this->$setter($value);
    }
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $token = Token::TEST_NAME;
    $lines = ["$token:{$this->getTestName()}"];
    $lines = array_merge($lines, array_map(function($item) { return (string) $item; }, $this->getRecords()->getArrayCopy()));
    return implode(PHP_EOL, $lines);
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param mixed $map A JSON map representing a line data.
   * @return Report The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    $transform = function(array $data) {
      return array_filter(array_map(function($item) { return Record::fromJSON($item); }, $data));
    };

    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static([
      'records' => isset($map->records) && is_array($map->records) ? $transform($map->records) : [],
      'testName' => isset($map->testName) && is_string($map->testName) ? $map->testName : ''
    ]);
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
      'records' => array_map(function(Record $item) { return $item->jsonSerialize(); }, $this->getRecords()->getArrayCopy())
    ];
  }

  /**
   * Parses the specified coverage data in [LCOV](http://ltp.sourceforge.net/coverage/lcov.php) format.
   * @param string $coverage The coverage data.
   * @return Report The resulting coverage report.
   * @throws \UnexpectedValueException A parsing error occurred.
   */
  public static function parse(string $coverage): self {
    $report = new static();
    $records = $report->getRecords();

    try {
      $record = new Record([
        'branches' => new BranchCoverage(),
        'functions' => new FunctionCoverage(),
        'lines' => new LineCoverage()
      ]);

      foreach (preg_split('/\r?\n/', $coverage) as $line) {
        $line = trim($line);
        if (!mb_strlen($line)) continue;

        $parts = explode(':', $line);
        if (count($parts) < 2 && $parts[0] != Token::END_OF_RECORD)
          throw new \UnexpectedValueException('Invalid LCOV line.');

        $token = array_shift($parts);
        $data = explode(',', implode(':', $parts));

        switch ($token) {
          case Token::TEST_NAME:
            $report->setTestName($data[0]);
            break;

          case Token::SOURCE_FILE:
            $record->setSourceFile($data[0]);
            break;

          case Token::FUNCTION_NAME:
            $record->getFunctions()->getData()->append(new FunctionData([
              'functionName' => $data[1],
              'lineNumber' => (int) $data[0]
            ]));
            break;

          case Token::FUNCTION_DATA:
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
            $record->getBranches()->getData()->append(new BranchData([
              'lineNumber' => (int) $data[0],
              'blockNumber' => (int) $data[1],
              'branchNumber' => (int) $data[2],
              'taken' => $data[3] == '-' ? 0 : (int) $data[3]
            ]));
            break;

          case Token::BRANCHES_FOUND:
            $record->getBranches()->setFound((int) $data[0]);
            break;

          case Token::BRANCHES_HIT:
            $record->getBranches()->setHit((int) $data[0]);
            break;

          case Token::LINE_DATA:
            $record->getLines()->getData()->append(new LineData([
              'lineNumber' => (int) $data[0],
              'executionCount' => (int) $data[1],
              'checksum' => count($data) >= 3 ? $data[2] : null
            ]));
            break;

          case Token::LINES_FOUND:
            $record->getLines()->setFound((int) $data[0]);
            break;

          case Token::LINES_HIT:
            $record->getLines()->setHit((int) $data[0]);
            break;

          case Token::END_OF_RECORD:
            $records->append($record);
            $record = new Record([
              'branches' => new BranchCoverage(),
              'functions' => new FunctionCoverage(),
              'lines' => new LineCoverage()
            ]);
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
   * Sets the record list.
   * @param Record[] $value The new record list.
   * @return Report This instance.
   */
  public function setRecords(array $value): self {
    $this->getRecords()->exchangeArray($value);
    return $this;
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
