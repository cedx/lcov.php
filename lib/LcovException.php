<?php
declare(strict_types=1);
namespace Lcov;

/**
 * An exception caused by a parsing error.
 */
class LcovException extends \UnexpectedValueException {

  /**
   * Creates a new client exception.
   * @param string $message A message describing the error.
   * @param \Throwable $previous The previous exception used for the exception chaining.
   */
  public function __construct($message, \Throwable $previous = null) {
    parent::__construct($message, 0, $previous);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    return sprintf('%s("%s")', static::class, $this->getMessage());
  }
}
