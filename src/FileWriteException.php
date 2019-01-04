<?php declare(strict_types=1); namespace BapCat\Persist;

use Exception;
use Throwable;

/**
 * A problem occurred while writing to a file
 */
class FileWriteException extends Exception {
  /**
   * @param  File            $file
   * @param  string          $message
   * @param  Throwable|null  $previous
   */
  public function __construct(File $file, string $message, ?Throwable $previous = null) {
    parent::__construct($file->path . ': ' . $message, 0, $previous);
  }
}
