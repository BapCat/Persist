<?php

require_once __DIR__ . '/FileCreatorTrait.php';
require_once __DIR__ . '/LocalMocksTrait.php';

use BapCat\Persist\Drivers\Local\LocalDirectory;
use BapCat\Persist\Drivers\Local\LocalFile;

class LocalDirectoryTest extends PHPUnit_Framework_TestCase {
  use FileCreatorTrait;
  use LocalMocksTrait;
  
  private $driver;
  
  public function setUp() {
    $this->createTestFiles();
    $this->driver = $this->mockLocalDriver(dirname($this->datadir));
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
  
  public function testGlob() {
    $directory = new LocalDirectory($this->driver, basename($this->datadir));
    $children = $directory->children('write*');
    $expected = ['write', 'writedir'];
    
    $this->assertSame(array_map(function($path) { return $path->name; }, iterator_to_array($children)), $expected);
  }
  
  public function testMoveEmptyDir() {
    $to_move = new LocalDirectory($this->driver, basename($this->datadir) . '/to-move');
    $moved   = new LocalDirectory($this->driver, basename($this->datadir) . '/moved');
    
    $this->assertFalse($moved->exists);
    
    $to_move->create();
    
    $this->assertTrue($to_move->move($moved));
    
    $this->assertFalse($to_move->exists);
    $this->assertTrue($moved->exists);
  }
  
  public function testMoveNonEmptyDir() {
    $to_move = new LocalDirectory($this->driver, basename($this->datadir) . '/ne-to-move');
    $moved   = new LocalDirectory($this->driver, basename($this->datadir) . '/ne-moved');
    
    $child       = new LocalFile($this->driver, $to_move->path . '/child');
    $moved_child = new LocalFile($this->driver, $moved->path . '/child');
    
    $this->assertFalse($moved->exists);
    
    $to_move->create();
    $child->create();
    
    $this->assertTrue($to_move->move($moved));
    
    $this->assertFalse($to_move->exists);
    $this->assertTrue($moved->exists);
    $this->assertFalse($child->exists);
    $this->assertTrue($moved_child->exists);
  }
  
  public function testCopyEmptyDir() {
    $to_copy = new LocalDirectory($this->driver, basename($this->datadir) . '/to-copy');
    $copied  = new LocalDirectory($this->driver, basename($this->datadir) . '/copied');
    
    $this->assertFalse($copied->exists);
    
    $to_copy->create();
    
    $this->assertTrue($to_copy->copy($copied));
    
    $this->assertTrue($to_copy->exists);
    $this->assertTrue($copied->exists);
  }
  
  public function testCopyNonEmptyDir() {
    $to_copy = new LocalDirectory($this->driver, basename($this->datadir) . '/ne-to-copy');
    $copied  = new LocalDirectory($this->driver, basename($this->datadir) . '/ne-copied');
    
    $child       = new LocalFile($this->driver, $to_copy->path . '/child');
    $moved_child = new LocalFile($this->driver, $copied->path . '/child');
    
    $child_dir       = new LocalDirectory($this->driver, $to_copy->path . '/child-dir');
    $moved_child_dir = new LocalDirectory($this->driver, $copied->path . '/child-dir');
    
    $this->assertFalse($copied->exists);
    
    $to_copy->create();
    $child->create();
    $child_dir->create();
    
    $this->assertTrue($to_copy->copy($copied));
    
    $this->assertTrue($to_copy->exists);
    $this->assertTrue($copied->exists);
    $this->assertTrue($child->exists);
    $this->assertTrue($moved_child->exists);
    $this->assertTrue($child_dir->exists);
    $this->assertTrue($moved_child_dir->exists);
  }
  
  public function testDelete() {
    $directory = new LocalDirectory($this->driver, basename($this->datadir));
    
    // Make the read-only stuff readable so it can be deleted
    chmod($this->readonly,  0755);
    chmod($this->readdir,   0755);
    chmod($this->writeonly, 0755);
    chmod($this->writedir,  0755);
    
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
