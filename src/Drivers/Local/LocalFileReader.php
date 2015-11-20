<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\FileReader;

/**
 * A reader capable of reading LocalFiles
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
class LocalFileReader extends FileReader {
  private $ptr;
  
  /**
   * {@inheritdoc}
   * 
   * @param  LocalFile  $file  The file to read from
   * @param  resource   $ptr   A file pointer to the file
   */
  public function __construct(LocalFile $file, $ptr) {
    parent::__construct($file);
    $this->ptr = $ptr;
  }
  
  /**
   * {@inheritdoc}
   */
  protected function getLength() {
    $size = @filesize($this->file->full_path);
    
    if($size === false) {
      //@TODO
      throw new \Exception("Error getting file length [{$this->file->full_path}]");
    }
    
    return $size;
  }
  
  /**
   * {@inheritdoc}
   */
  protected function getHasMore() {
    return $this->remaining != 0;
  }
  
  /**
   * {@inheritdoc}
   */
  protected function getRemaining() {
    return $this->length - @ftell($this->ptr);
  }
  
  /**
   * {@inheritdoc}
   */
  public function read($length = 0) {
    if($length === 0) {
      $length = $this->length;
    }
    
    $read = fread($this->ptr, $length);
    
    if($read === false) {
      //@TODO
      throw new \Exception("Error reading from file [{$this->file->full_path}]");
    }
    
    return $read;
  }
}