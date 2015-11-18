<?php namespace BapCat\Persist;

use BapCat\Propifier\PropifierTrait;

/**
 * Defines a basic element of a filesystem, which may be a file or directory
 * 
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
abstract class Path {
  use PropifierTrait;
  
  /**
   * The filesystem driver
   * 
   * @var Driver
   */
  private $driver;
  
  /**
   * The path of this file or directory
   * 
   * @var string
   */
  private $path;
  
  /**
   * The name of this file or directory
   * 
   * @var string
   */
  private $name;
  
  /**
   * Constructor
   * 
   * @param  Driver  $driver  The filesystem driver this file or directory belongs to
   * @param  string  $path    The path of this file or directory
   */
  public function __construct(Driver $driver, $path) {
    $this->driver = $driver;
    $this->path   = $path;
    
    $this->name = basename($path);
  }
  
  /**
   * Gets a string representation of this file or directory to a
   * 
   * @return  string  A string representation of this file or directory
   */
  public function __toString() {
    $class = basename(get_class());
    return "{$class}[{$this->path}]";
  }
  
  /**
   * Gets the driver of this file or directory
   * 
   * @return  Driver  The driver
   */
  protected function getDriver() {
    return $this->driver;
  }
  
  /**
   * Gets the path of this file or directory
   * 
   * @return  string  The path
   */
  protected function getPath() {
    return $this->path;
  }
  
  /**
   * Gets the name of this file or directory
   * 
   * @return  string  The name
   */
  protected function getName() {
    return $this->name;
  }
  
  /**
   * Checks if this file or directory exists
   * 
   * @return  boolean  True if the file or directory exists, false otherwise
   */
  protected function getExists() {
    return $this->driver->exists($this);
  }
  
  /**
   * Checks if this file or directory is a symlink
   * 
   * @return  boolean  True if the file or directory is a symlink, false otherwise
   */
  protected function getIsLink() {
    return $this->driver->isLink($this);
  }
  
  /**
   * Checks if this file or directory is readable
   * 
   * @return  boolean  True if the file or directory is readable, false otherwise
   */
  protected function getIsReadable() {
    return $this->driver->isReadable($this);
  }
  
  /**
   * Checks if this file or directory is writable
   * 
   * @return  boolean  True if the file or directory is writable, false otherwise
   */
  protected function getIsWritable() {
    return $this->driver->isWritable($this);
  }
  
  /**
   * Checks the last time this file or directory was modified
   * 
   * @return  DateTime  The last modified time
   */
  protected function getModified() {
    return $this->driver->modified($this);
  }
}
