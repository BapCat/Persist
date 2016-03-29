<?php namespace BapCat\Persist;

/**
 * Defines a directory in a persistent filesystem
 *
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
abstract class Directory extends Path {
  /**
   * Loads the children of this directory
   *
   * @return array<Path>  An array containing the children of this directory
   */
  protected abstract function loadChildren();
  
  /**
   * Gets the children of this directory
   *
   * @return array<Path>  An array containing the children of this directory
   */
  protected function getChildren() {
    return $this->loadChildren();
  }
  
  /**
   * Gets a child of this directory
   *
   * @param  string  $name  The name of the child to get
   *
   * @return Path    The child of this directory
   */
  protected function getChild($name) {
    $full_path = "{$this->path}/$name";
    
    if($this->driver->isDir($full_path)) {
      return $this->driver->getDirectory($full_path);
    }
    
    return $this->driver->getFile($full_path);
  }
  
  /**
   * Moves a directory to a new location
   *
   * @param  Directory  $dest  Where to move the directory
   *
   * @return  boolean  True on success, false otherwise
   */
  public abstract function move(Directory $dest);
  
  /**
   * Copies a directory to a new location
   *
   * @param  Directory  $dest  Where to copy the file
   *
   * @return  boolean  True on success, false otherwise
   */
  public abstract function copy(Directory $dest);
}
