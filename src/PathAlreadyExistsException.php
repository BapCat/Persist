<?php declare(strict_types=1); namespace BapCat\Persist;

use Exception;

/**
 * The file already exists.
 */
class PathAlreadyExistsException extends Exception {
  /** @var  string  $path */
  private $path;

  /**
   * @param  string  $path  The path
   */
  public function __construct(string $path) {
    parent::__construct("Path[$path] already exists");
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
