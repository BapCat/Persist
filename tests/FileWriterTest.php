<?php

require_once __DIR__ . '/_mocks.php';

use BapCat\Persist\PathNotFoundException;

class FileWriterTest extends PHPUnit_Framework_TestCase {
  public function testFileDoesntExist() {
    $this->setExpectedException(PathNotFoundException::class);
    
    $driver = mockDriver($this, false);
    $file = mockFile($this, $driver, '');
    
    mockFileWriter($this, $file);
  }
  
  public function testProperties() {
    $driver = mockDriver($this);
    $file = mockFile($this, $driver, '');
    
    $out = mockFileWriter($this, $file);
    
    $this->assertEquals($file, $out->file);
  }
  
  public function testWrite() {
    $driver = mockDriver($this);
    $file = mockFile($this, $driver, '');
    
    $out = mockFileWriter($this, $file);
    
    $this->assertEquals(10, $out->write('1234567890'));
  }
}
