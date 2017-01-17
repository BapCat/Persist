<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\Path;

use Countable;
use GlobIterator;
use IteratorAggregate;

class LocalPathCollection implements Countable, IteratorAggregate {
  /**
   * @var  LocalDirectory  $dir
   */
  private $dir;
  
  /**
   * @var  string  $glob
   */
  private $glob;
  
  /**
   * @param  LocalDirectory  $dir
   * @param  string          $glob  (optional)
   */
  public function __construct(LocalDirectory $dir, $glob = '*') {
    $this->dir  = $dir;
    $this->glob = $glob;
  }
  
  /**
   * Recursively move all files to a different directory
   * 
   * @param  LocalDirectory  $dest  The destination directory
   * 
   * @return  bool  True on success, false otherwise
   */
  public function move(LocalDirectory $dest) {
    $ret = true;
    
    foreach($this as $path) {
      $ret = $path->move($dest->child[$path->name]) && $ret;
    }
    
    return $ret;
  }
  
  /**
   * Recursively copy all files to a different directory
   * 
   * @param  LocalDirectory  $dest  The destination directory
   * 
   * @return  bool  True on success, false otherwise
   */
  public function copy(LocalDirectory $dest) {
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
    return iterator_count(new FilesystemIterator($this->dir->full_path));
  }
  
  /**
   * @return  Path[]  (generator) Yields all `Path`s in this directory
   */
  public function getIterator() {
    foreach(new GlobIterator("{$this->dir->full_path}/{$this->glob}") as $path) {
      yield $this->dir->child[$path->getFilename()];
    }
  }
}
