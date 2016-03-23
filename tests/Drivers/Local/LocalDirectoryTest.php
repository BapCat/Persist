<?php

require_once __DIR__ . '/_mocks.php';
require_once __DIR__ . '/FileCreatorTrait.php';

use BapCat\Persist\Drivers\Local\LocalDirectory;

class LocalDirectoryTest extends PHPUnit_Framework_TestCase {
  use FileCreatorTrait;
  
  private $driver;
  
  public function setUp() {
    $this->createTestFiles();
    $this->driver = mockLocalDriver($this, dirname($this->datadir));
  }
  
  public function tearDown() {
    $this->deleteTestFiles();
  }
  
  public function testLoadChildren() {
    $directory = new LocalDirectory($this->driver, basename($this->datadir));
    $children = $directory->children;
    $expected = $this->listFiles($this->datadir);
    
    foreach($children as $child) {
      $this->assertTrue(in_array($child->name, $expected));
    }
  }
  
  public function testDelete() {
    $directory = new LocalDirectory($this->driver, basename($this->datadir));
    
    // Make the read-only file readable so it can be deleted
    chmod($directory->child['read']->full_path, 0755);
    
    $this->assertTrue($directory->delete());
    $this->assertFalse(file_exists($directory->full_path));
    
    // Recreate data dir so next tests pass
    $directory->create();
  }
  
  public function testGetFullPath() {
    $directory = new LocalDirectory($this->driver, basename($this->datadir));
    
    $this->assertSame($this->datadir, $directory->full_path);
  }
}
