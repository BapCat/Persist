<?php

require_once __DIR__ . '/MocksTrait.php';

use BapCat\Persist\PathNotFoundException;

class FileReaderTest extends PHPUnit_Framework_TestCase {
  use MocksTrait;
  
  public function testFileDoesntExist() {
    $this->setExpectedException(PathNotFoundException::class);
    
    $driver = $this->mockDriver(false);
    $file = $this->mockFile($driver, '');
    
    $this->mockFileReader($file, 100);
  }
  
  public function testProperties() {
    $driver = $this->mockDriver();
    $file = $this->mockFile($driver, '');
    
    $in = $this->mockFileReader($file, 100);
    
    $this->assertEquals($file, $in->file);
    $this->assertTrue($in->has_more);
    
    $in = $this->mockFileReader($file, 0);
    $this->assertFalse($in->has_more);
  }
  
  public function testRead() {
    $driver = $this->mockDriver();
    $file = $this->mockFile($driver, '');
    
    $in = $this->mockFileReader($file, 100);
    
    $this->assertTrue($in->has_more);
    $this->assertEquals(50, strlen($in->read(50)));
    $this->assertTrue($in->has_more);
    $this->assertEquals(50, strlen($in->read(50)));
    $this->assertFalse($in->has_more);
  }
}
