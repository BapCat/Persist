<?php

require_once __DIR__ . '/_mocks.php';

class FileTest extends PHPUnit_Framework_TestCase {
  public function testProperties() {
    $filename = 'test';
    $driver = mockDriver($this);
    $file = mockFile($this, $driver, $filename);
    
    $this->assertEquals(100, $file->size);
  }
}
