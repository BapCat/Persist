<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\Directory;

use DirectoryIterator;
use FilesystemIterator;

class LocalDirectory extends Directory {
  public function __construct(LocalDriver $driver, $path) {
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
  protected function getFullPath() {
    return $this->driver->getFullPath($this->path);
  }
}
