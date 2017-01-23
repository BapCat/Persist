<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\StreamWrapper;

class LocalStreamWrapper implements StreamWrapper {
  const STREAM_KEY = 'persist-file';
  
  public $context;
  
  private $root;
  private $fp;
  
  public function dir_closedir() {
    if(!$this->fp) {
      return false;
    }
    
    closedir($this->fp);
    
    return true;
  }
  
  public function dir_opendir($path, $options) {
    $this->parseContext();
    
    //TODO: $options
    
    $this->fp = opendir($this->parsePath($path));
    return $this->fp !== false;
  }
  
  public function dir_readdir() {
    return readdir($this->fp);
  }
  
  public function dir_rewinddir() {
    if(!$this->fp) {
      return false;
    }
    
    rewinddir($this->fp);
    
    return true;
  }
  
  public function mkdir($path, $mode, $options) {
    $this->parseContext();
    
    //TODO: $options
    
    return mkdir($this->parsePath($path), $mode);
  }
  
  public function rename($path_from, $path_to) {
    $this->parseContext();
    
    return rename($this->parsePath($path_from), $this->parsePath($path_to));
  }
  
  public function rmdir($path, $options) {
    $this->parseContext();
    
    //TODO: $options
    
    return rmdir($this->parsePath($path));
  }
  
  public function stream_cast($cast_as) {
    return $this->fp;
  }
  
  public function stream_close() {
    fclose($this->fp);
    $this->fp = null;
  }
  
  public function stream_eof() {
    return feof($this->fp);
  }
  
  public function stream_flush() {
    return fflush($this->fp);
  }
  
  public function stream_lock($operation) {
    return flock($this->fp, $operation);
  }
  
  public function stream_metadata($path, $option, $value) {
    var_dump($this->context, $path, $option, $value);
  }
  
  public function stream_open($path, $mode, $options, &$opened_path) {
    $this->parseContext();
    
    //TODO: $options
    
    $this->fp = fopen($this->parsePath($path), $mode);
    return $this->fp !== false;
  }
  
  public function stream_read($count) {
    return fread($this->fp, $count);
  }
  
  public function stream_seek($offset, $whence = SEEK_SET) {
    return fseek($this->fp, $offset, $whence) === 0;
  }
  
  public function stream_set_option($option, $arg1, $arg2) {
    switch($option) {
      case STREAM_OPTION_BLOCKING:
        return stream_set_blocking($this->fp, $arg1);
      case STREAM_OPTION_READ_TIMEOUT:
        return stream_set_timeout($this->fp, $arg1, $arg2);
      case STREAM_OPTION_WRITE_BUFFER:
        return stream_set_write_buffer($this->fp, $arg1);
      case STREAM_OPTION_READ_BUFFER:
        return stream_set_read_buffer($this->fp, $arg1);
    }
  }
  
  public function stream_stat() {
    return fstat($this->fp);
  }
  
  public function stream_tell() {
    return ftell($this->fp);
  }
  
  public function stream_truncate($new_size) {
    return ftruncate($this->fp, $new_size);
  }
  
  public function stream_write($data) {
    return fwrite($this->fp, $data);
  }
  
  public function unlink($path) {
    $this->parseContext();
    
    return unlink($this->parsePath($path));
  }
  
  public function url_stat($path, $flags) {
    $this->parseContext();
    
    return stat($this->parsePath($path));
  }
  
  private function parseContext() {
    $options = stream_context_get_options($this->context);
    
    $this->root = $options[self::STREAM_KEY]['root'];
  }
  
  private function parsePath($path) {
    $url = parse_url($path);
    
    $path = $this->absolute("{$this->root}/{$url['host']}");
    
    if(substr($path, 0, strlen($this->root)) !== $this->root) {
      throw new PathOutsideOfRootException($path);
    }
    
    if(isset($url['path'])) {
      return $path . $url['path'];
    }
    
    return $path;
  }
  
  private function absolute($path) {
    $path = str_replace(['/', '\\'], '/', $path);
    $parts = array_filter(explode('/', $path), 'strlen');
    $absolutes = [];
    
    foreach($parts as $part) {
      if('.' === $part) { continue; }
      if('..' === $part) {
        array_pop($absolutes);
      } else {
        $absolutes[] = $part;
      }
    }
    
    $new_path = implode('/', $absolutes);
    
    if($path[0] === '/') {
      return '/' . $new_path;
    }
    
    return $new_path;
  }
}
