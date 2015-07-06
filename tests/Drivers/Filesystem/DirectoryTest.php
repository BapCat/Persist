<?php

require_once __DIR__ . '/../../_mocks.php';
require_once __DIR__ . '/FileCreatorTrait.php';

use BapCat\Persist\Drivers\Filesystem\FilesystemDirectory;

class DirectoryTest extends PHPUnit_Framework_TestCase {
  use FileCreatorTrait;
  
  private $driver;
  
  public function setUp() {
    $this->createTestFiles();
    $this->driver = mockDriver($this, dirname($this->datadir));
  }
  
  public function tearDown() {
    $this->deleteTestFiles();
  }
  
  public function testLoadChildren() {
    $directory = new FilesystemDirectory($this->driver, basename($this->datadir));
    $children = $directory->children;
    $expected = $this->listFiles($this->datadir);
    
    foreach($children as $child) {
      $this->assertTrue(in_array($child->name, $expected));
    }
  }
}
