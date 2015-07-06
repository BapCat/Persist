<?php namespace BapCat\Persist\Drivers\Filesystem;

use BapCat\Interfaces\Exceptions\PathNotFoundException;
use BapCat\Interfaces\Persist\Driver;
use BapCat\Interfaces\Persist\File;
use BapCat\Interfaces\Persist\Path;

use InvalidArgumentException;

class FilesystemDriver extends Driver {
  private $root;
  
  public function __construct($root) {
    $this->root = $root;
  }
  
  public function getRoot() {
    return $this->root;
  }
  
  protected function instantiateFile($path) {
    return new FilesystemFile($this, $path);
  }
  
  protected function instantiateDir($path) {
    return new FilesystemDirectory($this, $path);
  }
  
  public function isDir($path) {
    if(!is_string($path)) {
      throw new InvalidArgumentException("[$path] is not a valid path");
    }
    
    return is_dir("{$this->root}/$path");
  }
  
  public function isFile($path) {
    if(!is_string($path)) {
      throw new InvalidArgumentException("[$path] is not a valid path");
    }
    
    return is_file("{$this->root}/$path");
  }
  
  public function exists(Path $path) {
    return file_exists("{$this->root}/{$path->path}");
  }
  
  public function isLink(Path $path) {
    return is_link("{$this->root}/{$path->path}");
  }
  
  public function isReadable(Path $path) {
    return is_readable("{$this->root}/{$path->path}");
  }
  
  public function isWritable(Path $path) {
    return is_writable("{$this->root}/{$path->path}");
  }
  
  public function size(File $file) {
    $size = @filesize("{$this->root}/{$file->path}");
    
    if($size === false) {
      throw new PathNotFoundException($file);
    }
    
    return $size;
  }
}
