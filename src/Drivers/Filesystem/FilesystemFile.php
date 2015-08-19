<?php namespace BapCat\Persist\Drivers\Filesystem;

use BapCat\Interfaces\Persist\File;

class FilesystemFile extends File {
  public function __construct(FilesystemDriver $driver, $path) {
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
