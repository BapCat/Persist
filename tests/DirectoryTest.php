<?php

require_once __DIR__ . '/_mocks.php';

class DirectoryTest extends PHPUnit_Framework_TestCase {
  public function testProperties() {
    $dirname = 'test';
    $driver = mockDriver($this);
    $dir = mockDir($this, $driver, $dirname);
    
    $this->assertEquals(['a', 'b'], $dir->children);
  }
  
  public function testGetChild() {
    $dirname = 'test';
    $driver = mockDirDriver($this, $dirname);
    $dir = mockDir($this, $driver, $dirname);
    
    $this->assertEquals('test', $dir->child['test']->name);
    
    $driver = mockFileDriver($this, $dirname);
    $dir = mockDir($this, $driver, $dirname);
    
    $this->assertEquals('test', $dir->child['test']->name);
  }
}
