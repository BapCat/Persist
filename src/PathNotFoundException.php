<?php declare(strict_types=1); namespace BapCat\Persist;

use Exception;

/**
 * A path (file or directory) could not be found
 */
class PathNotFoundException extends Exception {
  /**
   * @var  string  $path
   */
  private $path;

  /**
   * @param  string  $path  The path that could not be found
   */
  public function __construct(string $path) {
    parent::__construct("[$path] does not exist");
    $this->path = $path;
  }

  /**
   * Gets the path that could not be found
   *
   * @return  string
   */
  public function getPath(): string {
    return $this->path;
  }
}
