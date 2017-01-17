<?php

require_once __DIR__ . '/MocksTrait.php';

class PathTest extends PHPUnit_Framework_TestCase {
  use MocksTrait;
  
  public function testProperties() {
    $driver = $this->mockDriver();
    $filename = 'test/a';
    
    $path = $this->mockPath($driver, $filename);
    
    $this->assertEquals($driver, $path->driver);
    $this->assertEquals($filename, $path->path);
    $this->assertEquals('a', $path->name);
    $this->assertTrue($path->exists);
    $this->assertTrue($path->is_link);
    $this->assertTrue($path->is_readable);
    $this->assertTrue($path->is_writable);
    $this->assertSame(0, $path->modified);
  }
}
