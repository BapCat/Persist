<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\Directory;

use DirectoryIterator;

class LocalDirectory extends Directory {
  public function __construct(LocalDriver $driver, $path) {
    parent::__construct($driver, $path);
  }
  
  public function create() {
    mkdir($this->driver->getFullPath($this->path), 0755);
  }
  
  protected function loadChildren() {
    $paths = [];
    
    foreach(new DirectoryIterator($this->full_path) as $path) {
      if($path->isDot()) {
        continue;
      }
      
      $full_path = "{$this->path}/$path";
      
      if($this->driver->isFile($full_path)) {
        $paths[] = $this->driver->getFile($full_path);
      } else {
        $paths[] = $this->driver->getDirectory($full_path);
      }
    }
    
    return $paths;
  }
  
  /**
   * {@inheritDoc}
   */
  public function delete() {
    foreach($this->children as $child) {
      if(!$child->delete()) {
        return false;
      }
    }
    
    return rmdir($this->full_path);
  }
  
  /* NON-STANDARD METHODS */
  protected function getFullPath() {
    return $this->driver->getFullPath($this->path);
  }
}
