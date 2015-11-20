<?php namespace BapCat\Persist;

/**
 * Defines a driver for a given persistent file storage medium
 * (eg. local filesystem, AWS, etc)
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
abstract class Driver {
  /**
   * Gets a file from the storage medium
   * 
   * @param   string  $path  The path of the file
   * 
   * @return  File    A file object
   */
  public function getFile($path) {
    if($this->isDir($path)) {
      throw new NotAFileException($path);
    }
    
    return $this->instantiateFile($path);
  }
  
  /**
   * Gets a directory from the storage medium
   * 
   * @param  string     $path  The path of the directory
   * 
   * @return Directory  A directory object
   */
  public function getDirectory($path) {
    if($this->isFile($path)) {
      throw new NotADirectoryException($path);
    }
    
    return $this->instantiateDir($path);
  }
  
  /**
   * Creates a File representation of a path
   * 
   * @param  string  $path  The path
   * 
   * @return File    An instance of File representing the path
   */
  protected abstract function instantiateFile($path);
  
  /**
   * Creates a Directory representation of a path
   * 
   * @param  string     $path  The path
   *
   * @return Directory  An instance of Directory representing the path
   */
  protected abstract function instantiateDir($path);
  
  /**
   * Create a file
   *
   * @param  string     $path  The path
   *
   * @return  File
   */
  public abstract function createFile($path);
  
  /**
   * Create a directory
   *
   * @param  string     $path  The path
   *
   * @return  Directory
   */
  public abstract function createDirectory($path);
  
  /**
   * Checks if a path is a directory
   * 
   * @param  string   $path  The Path to check
   * 
   * @return boolean  True if the path is a directory, false otherwise
   */
  public abstract function isDir($path);
  
  /**
   * Checks if a path is a file
   * 
   * @param  string   $path  The Path to check
   * 
   * @return boolean  True if the path is a file, false otherwise
   */
  public abstract function isFile($path);
  
  /**
   * Checks if a path exists
   * 
   * @param  Path     $path  The Path to check
   * 
   * @return boolean  True if the path exists, false otherwise
   */
  public abstract function exists(Path $path);
  
  /**
   * Checks if a path is a symlink
   * 
   * @param  Path     $path  The Path to check
   * 
   * @return boolean  True if the path is a symlink, false otherwise
   */
  public abstract function isLink(Path $path);
  
  /**
   * Checks if a path is readable
   * 
   * @param  Path     $path  The Path to check
   * 
   * @return boolean  True if the path is readable, false otherwise
   */
  public abstract function isReadable(Path $path);
  
  /**
   * Checks if a path is writable
   * 
   * @param  Path     $path  The Path to check
   * 
   * @return boolean  True if the path is writable, false otherwise
   */
  public abstract function isWritable(Path $path);
  
  /**
   * Gets the size of a file
   * 
   * @param  File  $file  The File to get the size of
   * 
   * @return int   The size of the file
   */
  public abstract function size(File $file);
  
  /**
   * Gets the time a Path was last modified
   * 
   * @param  Path      $path  The Path to get the modified time of
   * 
   * @return DateTime  When the path was last modified
   */
  public abstract function modified(Path $path);
}
