<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\Driver;
use BapCat\Persist\File;
use BapCat\Persist\Path;
use BapCat\Persist\PathNotFoundException;

use InvalidArgumentException;

class LocalDriver extends Driver {
  private $root;
  
  public function __construct($root) {
    $this->root = $root;
  }
  
  public function getRoot() {
    return $this->root;
  }
  
  protected function instantiateFile($path) {
    return new LocalFile($this, $path);
  }
  
  protected function instantiateDir($path) {
    return new LocalDirectory($this, $path);
  }
  
  /**
   * Create a file
   *
   * @param  string     $path  The path
   *
   * @return  LocalFile
   */
  public function createFile($path) {
    $localFile = $this->instantiateFile($path);
    $localFile->create();
    return $localFile;
  }
  
  /**
   * Create a directory
   *
   * @param  string     $path  The path
   *
   * @return  LocalDirectory
   */
  public function createDirectory($path) {
    $localDirectory = $this->instantiateDir($path);
    $localDirectory->create();
    return $localDirectory;
  }
  
  public function isDir($path) {
    if(!is_string($path)) {
      throw new InvalidArgumentException("[$path] is not a valid path");
    }
    
    return is_dir($this->getFullPath($path));
  }
  
  public function isFile($path) {
    if(!is_string($path)) {
      throw new InvalidArgumentException("[$path] is not a valid path");
    }
    
    return is_file($this->getFullPath($path));
  }
  
  public function exists(Path $path) {
    return file_exists($this->getFullPath($path->path));
  }
  
  public function isLink(Path $path) {
    return is_link($this->getFullPath($path->path));
  }
  
  public function isReadable(Path $path) {
    return is_readable($this->getFullPath($path->path));
  }
  
  public function isWritable(Path $path) {
    return is_writable($this->getFullPath($path->path));
  }
  
  public function size(File $file) {
    $size = @filesize($this->getFullPath($file->path));
    
    if($size === false) {
      throw new PathNotFoundException($file);
    }
    
    return $size;
  }
  
  public function modified(Path $path) {
    $time = @filemtime($this->getFullPath($path->path));
    
    if($time === false) {
      throw new PathNotFoundException($path);
    }
    
    return $time;
  }
  
  /* NON-STANDARD METHODS */
  public function getFullPath($path) {
    return "{$this->root}/$path";
  }
}
