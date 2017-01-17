<?php

require_once __DIR__ . '/MocksTrait.php';

class FileTest extends PHPUnit_Framework_TestCase {
  use MocksTrait;
  
  public function testProperties() {
    $filename = 'test';
    $driver = $this->mockDriver();
    $file = $this->mockFile($driver, $filename);
    
    $this->assertEquals(100, $file->size);
  }
}
