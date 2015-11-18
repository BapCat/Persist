<?php namespace BapCat\Persist;

/**
 * Defines a file in a presistent filesystem
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
abstract class File extends Path {
  /**
   * Gets the size of this file
   * 
   * @return  int  The size of the file
   */
  protected function getSize() {
    return $this->driver->size($this);
  }
  
  /**
   * Caches a copy of this file on the local filesystem (if it isn't already)
   * 
   * @return  File  A File instance that points to the local file
   */
  public abstract function makeLocal();
}
