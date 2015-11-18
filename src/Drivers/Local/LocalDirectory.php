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
      if($path->isDot()) {
        continue;
      }
      
      $full_path = "{$this->path}/$path";
      
      if($this->driver->isFile($full_path)) {
        $paths[] = $this->driver->getFile("{$this->path}/$full_path");
      } else {
        $paths[] = $this->driver->getDirectory("{$this->path}/$full_path");
      }
    }
    
    return $paths;
  }
  
  /* NON-STANDARD METHODS */
  protected function getFullPath() {
    return $this->driver->getFullPath($this->path);
  }
}