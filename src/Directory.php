<?php namespace BapCat\Persist;

class Directory extends Path {
  public function create($mode = 0777) {
    if($this->exists) {
      throw new PathAlreadyExistsException($this->path);
    }
    
    mkdir($this->path, $mode, false, $this->context);
    
    return $this;
  }
  
  protected function getFile($child) {
    return $this->driver->file("{$this->path}$child");
  }
  
  protected function getDir($child) {
    return $this->driver->dir("{$this->path}$child");
  }
  
  protected function getChild($child) {
    return $this->driver->get("{$this->path}$child");
  }
  
  protected function getChildren() {
    return new PathCollection($this, $this->context);
  }
  
  /**
   * Deletes this directory
   * 
   * @return  bool  True on success, false otherwise
   */
  public function delete() {
    if(!$this->children->delete()) {
      return false;
    }
    
    return rmdir($this->path, $this->context);
  }
}
