<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\File;
use BapCat\Persist\PathAlreadyExistsException;

/**
 * A file residing on a local filesystem
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
class LocalFile extends File {
  /**
   * {@inheritdoc}
   */
  public function __construct(LocalDriver $driver, $path) {
    parent::__construct($driver, $path);
  }
  
  /**
   * {@inheritdoc}
   */
  public function create() {
    if ( $this->driver->exists($this) ) {
      throw new PathAlreadyExistsException($this->path);
    }
    
    $handle = fopen($this->driver->getFullPath($this->path), 'w');
    fclose($handle);
  }
  
  /**
   * {@inheritdoc}
   */
  public function makeLocal() {
    return $this;
  }
  
  /**
   * {@inheritdoc}
   */
  public function read(callable $read) {
    $ptr = fopen($this->full_path, 'r');
    $read(new LocalFileReader($this, $ptr));
    fclose($ptr);
  }
  
  /**
   * {@inheritdoc}
   */
  public function write(callable $write) {
    $ptr = fopen($this->full_path, 'a');
    $write(new LocalFileWriter($this, $ptr));
    fclose($ptr);
  }
  
  /* NON-STANDARD METHODS */
  
  /**
   * Returns the full path of this file on the local filesystem
   * 
   * @return  string  The full path of this file
   */
  protected function getFullPath() {
    return $this->driver->getFullPath($this->path);
  }
}
