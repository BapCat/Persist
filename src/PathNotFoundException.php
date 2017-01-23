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
   * @var  string
   */
  private $path;
  
  /**
   * @param  string  $path
   */
  public function __construct($path) {
    parent::__construct("[$path] does not exist");
    $this->path = $path;
  }
  
  /**
   * @return  string
   */
  public function getPath() {
    return $this->path;
  }
}
