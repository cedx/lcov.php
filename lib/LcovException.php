<?php
declare(strict_types=1);
namespace Lcov;

/**
 * An exception caused by a parsing error.
 */
class LcovException extends \UnexpectedValueException {

  /**
   * @var int The offset in `$source` where the error was detected.
   */
  private $offset;

  /**
   * @var mixed The actual source input which caused the error.
   */
  private $source;

  /**
   * Creates a new client exception.
   * @param string $message A message describing the error.
   * @param mixed $source The actual source input which caused the error.
   * @param int $offset The offset in `$source` where the error was detected.
   * @param \Throwable $previous The previous exception used for the exception chaining.
   */
  public function __construct($message, $source = null, int $offset = -1, \Throwable $previous = null) {
    parent::__construct($message, 0, $previous);
    $this->offset = $offset;
    $this->source = $source;
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $values = ["\"{$this->getMessage()}\""];
    if (($offset = $this->getOffset()) >= 0) $values[] = "offset: $offset";
    return sprintf('%s(%s)', static::class, implode(', ', $values));
  }

  /**
   * Gets the offset in `$source` where the error was detected.
   * @return int The offset in `$source` where the error was detected.
   */
  public function getOffset(): int {
    return $this->offset;
  }

  /**
   * Gets the actual source input which caused the error.
   * @return mixed The actual source input which caused the error.
   */
  public function getSource() {
    return $this->source;
  }
}
