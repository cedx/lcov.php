<?php declare(strict_types=1);
namespace Lcov;

/** An exception caused by a parsing error. */
class LcovException extends \UnexpectedValueException {

  /** @var int The offset in `$source` where the error was detected. */
  private int $offset;

  /** @var string The actual source input which caused the error. */
  private string $source;

  /**
   * Creates a new client exception.
   * @param string $message A message describing the error.
   * @param string $source The actual source input which caused the error.
   * @param int $offset The offset in `$source` where the error was detected.
   * @param \Throwable|null $previous The previous exception used for the exception chaining.
   */
  function __construct($message, string $source = '', int $offset = 0, ?\Throwable $previous = null) {
    assert($offset >= 0);
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
   * @return string The actual source input which caused the error.
   */
  function getSource(): string {
    return $this->source;
  }
}
