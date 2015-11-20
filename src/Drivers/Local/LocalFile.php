<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\File;
use BapCat\Persist\PathAlreadyExistsException;

class LocalFile extends File {
  public function __construct(LocalDriver $driver, $path) {
    parent::__construct($driver, $path);
  }
  
  public function create() {
    
    if ( $this->driver->exists($this) ) {
      throw new PathAlreadyExistsException($this->path);
    }
    
    $handle = fopen($this->driver->getFullPath($this->path), 'w');
    fclose($handle);
  }
  
  public function makeLocal() {
    return $this;
  }
  
  public function read(callable $read) {
    $ptr = fopen($this->full_path, 'r');
    $read(new LocalFileReader($this, $ptr));
    fclose($ptr);
  }
  
  /* NON-STANDARD METHODS */
  protected function getFullPath() {
    return $this->driver->getFullPath($this->path);
  }
}
