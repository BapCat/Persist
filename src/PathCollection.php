<?php namespace BapCat\Persist;

use BapCat\Propifier\PropifierTrait;

use Countable;
use GlobIterator;
use IteratorAggregate;

class PathCollection implements Countable, IteratorAggregate {
  use PropifierTrait;
  
  /**
   * @var  Directory  $dir
   */
  private $dir;
  
  /**
   * @var  resource  $context
   */
  private $context;
  
  /**
   * @var  string  $glob
   */
  private $glob;
  
  /**
   * @param  Directory  $dir
   * @param  resource   $context
   * @param  string     $glob  (optional)
   */
  public function __construct(Directory $dir, $context, $glob = '*') {
    $this->dir     = $dir;
    $this->context = $context;
    $this->glob    = $glob;
  }
  
  /**
   * Recursively move all files to a different directory
   * 
   * @param  Directory  $dest  The destination directory
   * 
   * @return  bool  True on success, false otherwise
   */
  public function move(Directory $dest) {
    $ret = true;
    
    foreach($this as $path) {
      $ret = $path->move($dest->child[$path->name]) && $ret;
    }
    
    return $ret;
  }
  
  /**
   * Recursively copy all files to a different directory
   * 
   * @param  Directory  $dest  The destination directory
   * 
   * @return  bool  True on success, false otherwise
   */
  public function copy(Directory $dest) {
    $ret = true;
    
    foreach($this as $path) {
      $ret = $path->copy($dest->child[$path->name]) && $ret;
    }
    
    return $ret;
  }
  
  /**
   * Recursively delete all files in the directory
   * 
   * @return  bool  True on success, false otherwise
   */
  public function delete() {
    $ret = true;
    
    foreach($this as $path) {
      $ret = $path->delete() && $ret;
    }
    
    return $ret;
  }
  
  /**
   * Count the number of files in the directory
   * 
   * @return  int
   */
  public function count() {
    $count = 0;
    
    foreach($this->iterate() as $file) {
      $count++;
    }
    
    return $count;
  }
  
  protected function getCount() {
    return $this->count();
  }
  
  private function iterate() {
    if(!$this->dir->exists) {
      throw new PathNotFoundException($this->dir->path);
    }
    
    $fp = opendir($this->dir->path, $this->context);
    
    while(($file = readdir($fp)) !== false) {
      if($file !== '.' && $file !== '..') {
        yield $file;
      }
    }
    
    closedir($fp);
  }
  
  /**
   * @return  Path[]  (generator) Yields all `Path`s in this directory
   */
  public function getIterator() {
    foreach($this->iterate() as $file) {
      yield $this->dir->child[$file];
    }
  }
}
