<?php namespace BapCat\Persist;

use Exception;

/**
 * A path (file or directory) could not be found
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
class PathNotFoundException extends Exception {
  /**
   * The path that could not be found
   * 
   * @var string
   */
  private $path;
  
  /**
   * Constructor
   * 
   * @param string $path The path that could not be found
   */
  public function __construct($path) {
    parent::__construct("[$path] does not exist.");
    $this->path = $path;
  }
  
  /**
   * Gets the path that could not be found
   * 
   * @return string The path that could not be found
   */
  public function getPath() {
    return $this->path;
  }
}
