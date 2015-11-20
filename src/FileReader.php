<?php namespace BapCat\Persist;

use BapCat\Persist\PathNotFoundException;
use BapCat\Propifier\PropifierTrait;

/**
 * Defines a class capable of reading from a file
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
abstract class FileReader {
  use PropifierTrait;
  
  /**
   * The file to read from
   * 
   * @var File
   */
  private $file;
  
  /**
   * Constructor
   * 
   * @param  File  $file  The file to read from
   */
  public function __construct(File $file) {
    if(!$file->exists) {
      throw new PathNotFoundException($file);
    }
    
    $this->file = $file;
  }
  
  /**
   * Gets the file this reader will read from
   * 
   * @return File  The file
   */
  protected function getFile() {
    return $this->file;
  }
  
  /**
   * Gets the length of the file
   * 
   * @return  integer  The length of the file
   */
  protected abstract function getLength();
  
  /**
   * Get the number of remaining character
   * 
   * @return  integer  The number of remaining characters
   */
  protected abstract function getRemaining();
  
  /**
   * Checks if the file has data left that hasn't been read yet
   * 
   * @return  string  True if there is more data in the file, false otherwise
   */
  protected abstract function getHasMore();
  
  /**
   * Reads an arbitrary block of data from the file
   * 
   * @param  int     $length  The amount of data to read from the file
   * 
   * @return string  The data read from the file
   */
  public abstract function read($length = 0);
}
