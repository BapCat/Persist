<?php namespace BapCat\Persist\Drivers\Filesystem;

use BapCat\Interfaces\Persist\Directory;

use DirectoryIterator;
use FilesystemIterator;

class FilesystemDirectory extends Directory {
  public function __construct(FilesystemDriver $driver, $path) {
    parent::__construct($driver, $path);
  }
  
  protected function loadChildren() {
    $paths = [];
    
    foreach(new DirectoryIterator("{$this->driver->getRoot()}/{$this->path}") as $path) {
      if($path->isDot()) { continue; }
      $paths[] = $this->driver->get("{$this->path}/$path");
    }
    
    return $paths;
  }
  
  /* NON-STANDARD METHODS */
  protected function getFullPath($path) {
    return $this->driver->getFullPath($this);
  }
}
