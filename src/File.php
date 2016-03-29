<?php namespace BapCat\Persist;

/**
 * Defines a file in a persistent filesystem
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
  
  /**
   * Opens a file for reading
   *
   * @param  callable<FileReader>  $read  Called once the file is opened
   */
  public abstract function read(callable $read);
  
  /**
   * Opens a file and returns its contents
   *
   * @return  string  The contents of the file
   */
  public abstract function readAll();
  
  /**
   * Opens a file for writing
   *
   * @param  callable<FileWriter>  $write  Called once the file is opened
   */
  public abstract function write(callable $write);
  
  /**
   * Opens a file and writes to it
   *
   * @param  string  $contents  The contents to write
   *
   * @return  integer  The length of the data written
   */
  public abstract function writeAll($contents);
  
  /**
   * Moves a file to a new location
   *
   * @param  File  $dest  Where to move the file
   *
   * @return  boolean  True on success, false otherwise
   */
  public abstract function move(File $dest);
  
  /**
   * Copies a file to a new location
   *
   * @param  File  $dest  Where to copy the file
   *
   * @return  boolean  True on success, false otherwise
   */
  public abstract function copy(File $dest);
}
