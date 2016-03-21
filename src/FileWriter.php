<?php namespace BapCat\Persist;

use BapCat\Persist\PathNotFoundException;
use BapCat\Propifier\PropifierTrait;

/**
 * Defines a class capable of writing to a file
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
abstract class FileWriter {
  use PropifierTrait;
  
  /**
   * The file to write to
   * 
   * @var File
   */
  private $file;
  
  /**
   * Constructor
   * 
   * @param  File  $file  THe file to write to
   */
  public function __construct(File $file) {
    if(!$file->exists) {
      throw new PathNotFoundException($file);
    }
    
    $this->file = $file;
  }
  
  /**
   * Gets the file this writer will write to
   * 
   * @return File  The file
   */
  protected function getFile() {
    return $this->file;
  }
  
  /**
   * Writes an arbitrary block of data to the file
   * 
   * @param  mixed  $data  The data to write to the file
   * 
   * @return  int  The number of bytes written
   */
  public abstract function write($data);
}
