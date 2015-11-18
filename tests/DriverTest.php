<?php

require_once __DIR__ . '/_mocks.php';

use BapCat\Persist\File;
use BapCat\Persist\Directory;

class DriverTest extends PHPUnit_Framework_TestCase {
  public function testGetFile() {
    $filename = 'test';
    $driver = mockFileDriver($this, $filename);
    
    $dir = $driver->getFile($filename);
    
    $this->assertInstanceOf(File::class, $dir);
  }
  
  public function testGetDirectory() {
    $dirname = 'test';
    $driver = mockDirDriver($this, $dirname);
    
    $dir = $driver->getDirectory($dirname);
    
    $this->assertInstanceOf(Directory::class, $dir);
  }
}
