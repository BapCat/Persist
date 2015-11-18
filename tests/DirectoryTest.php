<?php

require_once __DIR__ . '/_mocks.php';

class DirectoryTest extends PHPUnit_Framework_TestCase {
  public function testProperties() {
    $dirname = 'test';
    $driver = mockDriver($this);
    $dir = mockDir($this, $driver, $dirname);
    
    $this->assertEquals(['a', 'b'], $dir->children);
  }
}
