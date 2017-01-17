<?php

require_once __DIR__ . '/MocksTrait.php';

class DirectoryTest extends PHPUnit_Framework_TestCase {
  use MocksTrait;
  
  public function testProperties() {
    $dirname = 'test';
    $driver = $this->mockDriver();
    $dir = $this->mockDir($driver, $dirname);
    
    $this->assertEquals(['a', 'b'], $dir->children);
  }
  
  public function testGetChildren() {
    $dirname = 'test';
    $driver = $this->mockDirDriver($dirname);
    $dir = $this->mockDir($driver, $dirname);
    
    $this->assertSame(['a', 'b'], $dir->children);
  }
  
  public function testItrChildren() {
    $dirname = 'test';
    $driver = $this->mockDirDriver($dirname);
    $dir = $this->mockDir($driver, $dirname);
    
    $files = [];
    
    foreach($dir->children as $child) {
      $files[] = $child;
    }
    
    $this->assertSame(['a', 'b'], $files);
  }
  
  public function testGetChild() {
    $dirname = 'test';
    $driver = $this->mockDirDriver($dirname);
    $dir = $this->mockDir($driver, $dirname);
    
    $this->assertEquals('test', $dir->child['test']->name);
    
    $driver = $this->mockFileDriver($dirname);
    $dir = $this->mockDir($driver, $dirname);
    
    $this->assertEquals('test', $dir->child['test']->name);
  }
}
