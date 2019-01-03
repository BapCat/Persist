<?php declare(strict_types=1); namespace BapCat\Persist;

use Exception;

/**
 * The path specified is not a file
 */
class NotAFileException extends Exception {
  /** @var  string  $path */
  private $path;

  /**
   * @param  string  $path  The path
   */
  public function __construct(string $path) {
    parent::__construct("[$path] exists, but is not a file");
    $this->path = $path;
  }

  /**
   * Gets the path
   *
   * @return  string
   */
  public function getPath(): string {
    return $this->path;
  }
}
