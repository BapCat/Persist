<?php namespace BapCat\Persist;

use Exception;

class PathAlreadyExistsException extends Exception {
  /**
   * @var string
   */
  private $path;
  
  /**
   * @param  string  $path
   */
  public function __construct($path) {
    parent::__construct("Path[$path] already exists");
    $this->path = $path;
  }
  
  /**
   * @return  string
   */
  public function getPath() {
    return $this->path;
  }
}
