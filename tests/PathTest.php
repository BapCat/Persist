<?php

require_once __DIR__ . '/_mocks.php';

class PathTest extends PHPUnit_Framework_TestCase {
  public function testProperties() {
    $driver = mockDriver($this);
    $filename = 'test/a';
    
    $path = mockPath($this, $driver, $filename);
    
    $this->assertEquals($driver, $path->driver);
    $this->assertEquals($filename, $path->path);
    $this->assertEquals('a', $path->name);
    $this->assertTrue($path->exists);
    $this->assertTrue($path->is_link);
    $this->assertTrue($path->is_readable);
    $this->assertTrue($path->is_writable);
  }
}
