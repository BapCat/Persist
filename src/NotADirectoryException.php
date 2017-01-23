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
   * @var  string
   */
  private $path;
  
  /**
   * @param  string  $path
   */
  public function __construct($path) {
    parent::__construct("[$path] is not a directory");
    $this->path = $path;
  }
  
  /**
   * @return  string
   */
  public function getPath() {
    return $this->path;
  }
}
