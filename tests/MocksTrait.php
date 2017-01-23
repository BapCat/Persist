<?php

use BapCat\Persist\Directory;
use BapCat\Persist\Driver;
use BapCat\Persist\File;
use BapCat\Persist\Path;
use BapCat\Persist\FileReader;
use BapCat\Persist\FileWriter;

trait MocksTrait {
  public function mockFileDriver($filename) {
    $driver = $this->mockDriver();
    $file = $this->mockFile($driver, $filename);
    
    $driver
      ->method('isFile')
      ->willReturn(true);
    
    $driver
      ->method('isDir')
      ->willReturn(false);
    
    $driver
      ->method('instantiateFile')
      ->willReturn($file);
    
    return $driver;
  }

  public function mockDirDriver($filename) {
    $driver = $this->mockDriver();
    $dir = $this->mockDir($driver, $filename);
    
    $driver
      ->method('isFile')
      ->willReturn(false);
    
    $driver
      ->method('isDir')
      ->willReturn(true);
    
    $driver
      ->method('instantiateDir')
      ->willReturn($dir);
    
    return $driver;
  }

  public function mockDriver($exists = true) {
    $driver = $this
      ->getMockBuilder(Driver::class)
      ->disableOriginalConstructor()
      ->setMethods(['exists'])
      ->getMockForAbstractClass();
    
    $driver
      ->method('exists')
      ->willReturn($exists);
    
    /*$driver
      ->method('size')
      ->willReturn(100);
    
    $driver
      ->method('isLink')
      ->willReturn(true);
    
    $driver
      ->method('isReadable')
      ->willReturn(true);
    
    $driver
      ->method('isWritable')
      ->willReturn(true);
    
    $driver
      ->method('modified')
      ->willReturn(0);
    */
    
    return $driver;
  }

  public function mockPath(Driver $driver, $filename) {
    $path = $this->getMockForAbstractClass(Path::class, [$driver, null, $filename]);
    return $path;
  }

  public function mockFile(Driver $driver, $filename) {
    $file = $this->getMockForAbstractClass(File::class, [$driver, $filename]);
    return $file;
  }

  public function mockDir(Driver $driver, $filename) {
    $dir = $this->getMockForAbstractClass(Directory::class, [$driver, $filename]);
    
    $dir
      ->method('loadChildren')
      ->willReturn(['a', 'b']);
    
    return $dir;
  }

  public function mockFileReader(File $file, $length) {
    $in = $this->getMockForAbstractClass(FileReader::class, [$file]);
    
    $remaining = $length;
    
    $in
      ->method('getHasMore')
      ->will($this->returnCallback(function() use(&$remaining) {
        return $remaining > 0;
      }));
    
    $in
      ->method('read')
      ->will($this->returnCallback(function($length = 0) use(&$remaining) {
        $remaining -= $length;
        return openssl_random_pseudo_bytes($length);
      }));
    
    return $in;
  }

  public function mockFileWriter(File $file) {
    $in = $this->getMockForAbstractClass(FileWriter::class, [$file]);
    
    $in
      ->method('write')
      ->will($this->returnCallback(function($data) {
        return strlen($data);
      }));
    
    return $in;
  }
}
