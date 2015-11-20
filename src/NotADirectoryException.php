<?php namespace BapCat\Persist;

use Exception;

/**
 * The path specified is not a directory
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
class NotADirectoryException extends Exception {
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
    parent::__construct("[$path] exists, but is not a directory");
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
