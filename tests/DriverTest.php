<?php

require_once __DIR__ . '/MocksTrait.php';

use BapCat\Persist\File;
use BapCat\Persist\Directory;

class DriverTest extends PHPUnit_Framework_TestCase {
  use MocksTrait;
  
  public function testGetFile() {
    $filename = 'test';
    $driver = $this->mockFileDriver($filename);
    
    $dir = $driver->getFile($filename);
    
    $this->assertInstanceOf(File::class, $dir);
  }
  
  public function testGetDirectory() {
    $dirname = 'test';
    $driver = $this->mockDirDriver($dirname);
    
    $dir = $driver->getDirectory($dirname);
    
    $this->assertInstanceOf(Directory::class, $dir);
  }
}
