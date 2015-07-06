<?php

trait FileCreatorTrait {
  protected $datadir;
  protected $dirname;
  protected $filename;
  protected $filelen;
  protected $linkname;
  protected $dirlinkname;
  protected $readonly;
  protected $writeonly;
  
  public function createTestFiles() {
    $this->datadir   = __DIR__ . '/data';
    $this->dirname   = 'dir';
    $this->filename  = 'test';
    $this->linkname  = 'link';
    $this->readonly  = 'read';
    $this->writeonly = 'write';
    $this->readdir   = 'readdir';
    $this->writedir  = 'writedir';
    
    if(file_exists($this->datadir)) {
      chdir($this->datadir);
      $this->deleteTestFiles();
    }
    
    mkdir($this->datadir);
    chdir($this->datadir);
    
    mkdir($this->dirname, 0777, true);
    file_put_contents($this->filename, 'This is a test');
    $this->filelen = filesize($this->filename);
    
    symlink($this->datadir . '/' . $this->filename, $this->datadir . '/' . $this->linkname);
    
    file_put_contents($this->readonly, 'This is read-only');
    file_put_contents($this->writeonly, 'This is write-only');
    
    chmod($this->readonly,  0444);
    chmod($this->writeonly, 0222);
    
    mkdir($this->readdir,  0444);
    mkdir($this->writedir, 0222);
  }
  
  public function deleteTestFiles() {
    chdir($this->datadir);
    
    @chmod($this->readonly,  0777);
    @chmod($this->writeonly, 0777);
    @chmod($this->readdir,   0777);
    @chmod($this->writedir,  0777);
    
    $this->deleteDir($this->datadir);
  }
  
  private function deleteDir($dirPath) {
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
      if($path->isDir() && !$path->isLink()) {
        $this->deleteDir($path->getPathname());
      } else {
        unlink($path->getPathname());
      }
    }
    
    rmdir($dirPath);
  }
  
  private function listFiles($dirPath) {
    $blacklist = ['.', '..'];
    
    return array_filter(scandir($dirPath), function($path) use($blacklist) {
      return !in_array($path, $blacklist);
    });
  }
}
