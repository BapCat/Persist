<?php namespace BapCat\Persist;

use BapCat\Propifier\PropifierTrait;

abstract class Driver {
  use PropifierTrait;
  
  private $stream_key;
  private $context;
  
  protected function __construct($stream_key, $context) {
    $this->stream_key = $stream_key;
    $this->context    = $context;
  }
  
  protected function getRoot() {
    return $this->dir('');
  }
  
  public function file($path) {
    return new File($this, $this->context, $this->toUrl($path));
  }
  
  public function dir($path) {
    return new Directory($this, $this->context, $this->toUrl($path));
  }
  
  public function get($path) {
    if($this->isDir($path)) {
      return $this->dir($path);
    }
    
    if($this->isFile($path)) {
      return $this->file($path);
    }
    
    throw new PathNotFoundException($path);
  }
  
  public function exists($path) {
    return $this->isDir($path) || $this->isFile($path);
  }
  
  public function isFile($path) {
    if($this->isDir($path)) {
      return false;
    }
    
    $path = $this->toUrl($path);
    
    $fp = @fopen($path, 'r', false, $this->context);
    
    if($fp) {
      fclose($fp);
      return true;
    }
    
    return false;
  }
  
  public function isDir($path) {
    $path = $this->toUrl($path);
    
    $fp = @opendir($path, $this->context);
    
    if($fp) {
      closedir($fp);
      return true;
    }
    
    return false;
  }
  
  private function toUrl($path) {
    $url = parse_url($path);
    $url['scheme'] = $this->stream_key;
    
    $path = $url['scheme'] . '://';
    
    if(isset($url['host'])) {
      $path .= $url['host'] . '/';
    }
    
    if(isset($url['path'])) {
      $path .= $url['path'];
    }
    
    return $path;
  }
}
