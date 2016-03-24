<?php

require_once __DIR__ . '/_mocks.php';

class DirectoryTest extends PHPUnit_Framework_TestCase {
  public function testProperties() {
    $dirname = 'test';
    $driver = mockDriver($this);
    $dir = mockDir($this, $driver, $dirname);
    
    $this->assertEquals(['a', 'b'], $dir->children);
  }
  
  public function testGetChildren() {
    $dirname = 'test';
    $driver = mockDirDriver($this, $dirname);
    $dir = mockDir($this, $driver, $dirname);
    
    $this->assertSame(['a', 'b'], $dir->children);
  }
  
  public function testItrChildren() {
    $dirname = 'test';
    $driver = mockDirDriver($this, $dirname);
    $dir = mockDir($this, $driver, $dirname);
    
    $files = [];
    
    foreach($dir->children as $child) {
      $files[] = $child;
    }
    
    $this->assertSame(['a', 'b'], $files);
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
