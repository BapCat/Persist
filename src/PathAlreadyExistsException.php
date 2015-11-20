<?php namespace BapCat\Persist;

use Exception;

/**
 * The file already exists.
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
class PathAlreadyExistsException extends Exception {
  /**
   * The path
   * 
   * @var string
   */
  private $path;
  
  /**
   * Constructor
   * 
   * @param  string  $path  The path
   */
  public function __construct($path) {
    parent::__construct("Path[$path] already exists");
    $this->path = $path;
  }
  
  /**
   * Gets the path
   * 
   * @return  string  The path
   */
  public function getPath() {
    return $this->path;
  }
}