<?php

require_once __DIR__ . '/MocksTrait.php';

use BapCat\Persist\PathNotFoundException;

class FileWriterTest extends PHPUnit_Framework_TestCase {
  use MocksTrait;
  
  public function testFileDoesntExist() {
    $this->setExpectedException(PathNotFoundException::class);
    
    $driver = $this->mockDriver(false);
    $file = $this->mockFile($driver, '');
    
    $this->mockFileWriter($file);
  }
  
  public function testProperties() {
    $driver = $this->mockDriver();
    $file = $this->mockFile($driver, '');
    
    $out = $this->mockFileWriter($file);
    
    $this->assertEquals($file, $out->file);
  }
  
  public function testWrite() {
    $driver = $this->mockDriver();
    $file = $this->mockFile($driver, '');
    
    $out = $this->mockFileWriter($file);
    
    $this->assertEquals(10, $out->write('1234567890'));
  }
}
