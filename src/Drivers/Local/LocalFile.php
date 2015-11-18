<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\File;

class LocalFile extends File {
  public function __construct(LocalDriver $driver, $path) {
    parent::__construct($driver, $path);
  }
  
  public function makeLocal() {
    return $this;
  }
  
  /* NON-STANDARD METHODS */
  protected function getFullPath() {
    return $this->driver->getFullPath($this->path);
  }
}
