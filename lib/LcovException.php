<?php declare(strict_types=1);
namespace Lcov;

/** An exception caused by a parsing error. */
class LcovException extends \UnexpectedValueException {

  /** @var int The offset in `$source` where the error was detected. */
  private $offset;

  /** @var mixed The actual source input which caused the error. */
  private $source;

  /**
   * Creates a new client exception.
   * @param string $message A message describing the error.
   * @param mixed $source The actual source input which caused the error.
   * @param int $offset The offset in `$source` where the error was detected.
   * @param \Throwable|null $previous The previous exception used for the exception chaining.
   */
  function __construct($message, $source = null, int $offset = -1, \Throwable $previous = null) {
    parent::__construct($message, 0, $previous);
    $this->offset = $offset;
    $this->source = $source;
  }

  /**
   * Gets the offset in `$source` where the error was detected.
   * @return int The offset in `$source` where the error was detected.
   */
  function getOffset(): int {
    return $this->offset;
  }

  /**
   * Gets the actual source input which caused the error.
   * @return mixed The actual source input which caused the error.
   */
  function getSource() {
    return $this->source;
  }
}
