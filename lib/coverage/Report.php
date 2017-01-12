<?php
/**
 * Implementation of the `lcov\coverage\Report` class.
 */
namespace lcov\coverage;
use lcov\{Token};

/**
 * Provides the coverage data of a source file.
 */
class Report {

  /**
   * @var Record[] The record list.
   */
  private $records = [];

  /**
   * @var string The test name.
   */
  private $testName = '';

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    foreach ($config as $property => $value) {
      $setter = "set$property";
      if(method_exists($this, $setter)) $this->$setter($value);
    }
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $token = Token::TEST_NAME;
    $lines = ["$token:{$this->getTestName()}"];
    $lines = array_merge($lines, array_map(function($item) { return (string) $item; }, $this->getRecords()));
    return implode(PHP_EOL, $lines);
  }

  /**
   * Creates a new line data from the specified JSON map.
   * @param mixed $map A JSON map representing a line data.
   * @return Report The instance corresponding to the specified JSON map, or `null` if a parsing error occurred.
   */
  public static function fromJSON($map) {
    $transform = function(array $data) {
      $items = array_map(function($item) { return Record::fromJSON($item); }, $data);
      return array_filter($items, function($item) { return isset($item); });
    };

    if (is_array($map)) $map = (object) $map;
    return !is_object($map) ? null : new static([
      'records' => isset($map->records) && is_array($map->records) ? $transform($map->records) : [],
      'testName' => isset($map->test) && is_string($map->test) ? $map->test : ''
    ]);
  }

  /**
   * Gets the record list.
   * @return Record[] The record list.
   */
  public function getRecords(): array {
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
      'test' => $this->getTestName(),
      'records' => $this->getRecords()
    ];
  }

  /**
   * Sets the record list.
   * @param Record[] $value The new record list.
   * @return Report This instance.
   */
  public function setChecksum(array $value): self {
    $this->records = $value;
    return $this;
  }

  /**
   * Sets the test name.
   * @param string $value The new test name.
   * @return Report This instance.
   */
  public function setExecutionCount(string $value): self {
    $this->testName = $value;
    return $this;
  }
}
