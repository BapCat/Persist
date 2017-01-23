<?php namespace BapCat\Persist;

use BapCat\Propifier\PropifierTrait;

abstract class Path {
  use PropifierTrait;
  
  /**
   * @var  Driver
   */
  protected $driver;
  
  /**
   * @var  resource
   */
  protected $context;
  
  /**
   * @var  string  The full path
   */
  private $path;
  
  /**
   * @var  string  The filename only
   */
  private $name;
  
  /**
   * @param  Driver   $driver
   * @param  context  $context
   * @param  string   $path
   */
  public function __construct(Driver $driver, $context, $path) {
    $this->driver  = $driver;
    $this->context = $context;
    $this->path    = $path;
    $this->name    = basename($path);
  }
  
  /**
   * @return  string  A string representation of this path
   */
  public function __toString() {
    $class = basename(get_class());
    return "{$class}[{$this->path}]";
  }
  
  /**
   * @return  string  The path
   */
  protected function getPath() {
    return $this->path;
  }
  
  /**
   * @return  string  The name
   */
  protected function getName() {
    return $this->name;
  }
  
  /**
   * @return  Directory
   */
  protected function getParent() {
    return $this->driver->directory(dirname($this->path));
  }
  
  /**
   * @return  bool  Whether or not this path exists
   */
  protected function getExists() {
    return $this->driver->exists($this->path);
  }
  
  /**
   * @return  bool  True if the path is readable
   */
  protected function getIsReadable() {
    throw new \Exception();
  }
  
  /**
   * @return  bool  True if the path is writable
   */
  protected function getIsWritable() {
    throw new \Exception();
  }
  
  /**
   * @return  DateTime  The last modified time
   */
  protected function getModified() {
    return $this->driver->modified($this);
  }
  
  public function move(Path $dest) {
    //TODO
  }
  
  public function copy(Path $dest) {
    //TODO
  }
  
  /**
   * Deletes this path
   *
   * @return  bool  True on success, false otherwise
   */
  public function delete() {
    return unlink($this->path, $this->context);
  }
}
