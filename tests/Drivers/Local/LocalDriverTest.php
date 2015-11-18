<?php

require_once __DIR__ . '/../../_mocks.php';
require_once __DIR__ . '/FileCreatorTrait.php';

use BapCat\Persist\Directory;
use BapCat\Persist\File;
use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\PathNotFoundException;

class DriverTest extends PHPUnit_Framework_TestCase {
  use FileCreatorTrait;
  
  private $driver;
  
  public function setUp() {
    $this->createTestFiles();
    $this->driver = new LocalDriver($this->datadir);
  }
  
  public function tearDown() {
    $this->deleteTestFiles();
  }
  
  public function testGet() {
    $file = $this->driver->get($this->filename);
    $this->assertInstanceOf(File::class, $file);
    
    $dir = $this->driver->get($this->dirname);
    $this->assertInstanceOf(Directory::class, $dir);
  }
  
  public function testIsFile() {
    $this->assertTrue ($this->driver->isFile($this->filename));
    $this->assertFalse($this->driver->isFile($this->dirname));
  }
  
  public function testIsFileWithInvalidPath() {
    $this->setExpectedException(InvalidArgumentException::class);
    $this->driver->isFile(null);
  }
  
  public function testIsDir() {
    $this->assertTrue ($this->driver->isDir($this->dirname));
    $this->assertFalse($this->driver->isDir($this->filename));
  }
  
  public function testIsDirWithInvalidPath() {
    $this->setExpectedException(InvalidArgumentException::class);
    $this->driver->isDir(null);
  }
  
  public function testFileExists() {
    $file = mockFile($this, $this->driver, $this->filename);
    $this->assertTrue($this->driver->exists($file));
  }
  
  public function testDirExists() {
    $dir = mockDir($this, $this->driver, $this->dirname);
    $this->assertTrue($this->driver->exists($dir));
  }
  
  public function testDoesntExist() {
    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    $this->assertFalse($this->driver->exists($file));
  }
  
  public function testFileIsLink() {
    $file = mockFile($this, $this->driver, $this->linkname);
    $this->assertTrue($this->driver->isLink($file));
  }
  
  public function testFileIsNotLink() {
    $file = mockFile($this, $this->driver, $this->filename);
    $this->assertFalse($this->driver->isLink($file));
  }
  
  public function testDirIsLink() {
    //@TODO: need to figure out why unlink(...) doesn't work on directory symlinks on Windows
    //$dir = mockDir(...)
  }
  
  public function testDirIsNotLink() {
    $dir = mockDir($this, $this->driver, $this->dirname);
    $this->assertFalse($this->driver->isLink($dir));
  }
  
  public function testIsLinkPathDoesntExist() {
    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    $this->assertFalse($this->driver->isLink($file));
  }
  
  public function testFileIsReadable() {
    $file = mockFile($this, $this->driver, $this->readonly);
    $this->assertTrue($this->driver->isReadable($file));
  }
  
  public function testFileIsNotReadable() {
    $file = mockFile($this, $this->driver, $this->writeonly);
    $this->assertFalse($this->driver->isReadable($file));
  }
  
  public function testDirIsReadable() {
    $dir = mockDir($this, $this->driver, $this->readdir);
    $this->assertTrue($this->driver->isReadable($dir));
  }
  
  public function testDirIsNotReadable() {
    $dir = mockDir($this, $this->driver, $this->writedir);
    $this->assertFalse($this->driver->isReadable($dir));
  }
  
  public function testFileIsWritable() {
    $file = mockFile($this, $this->driver, $this->writeonly);
    $this->assertTrue($this->driver->isWritable($file));
  }
  
  public function testFileIsNotWritable() {
    $file = mockFile($this, $this->driver, $this->readonly);
    $this->assertFalse($this->driver->isWritable($file));
  }
  
  public function testDirIsWritable() {
    $dir = mockDir($this, $this->driver, $this->writedir);
    $this->assertTrue($this->driver->isWritable($dir));
  }
  
  public function testDirIsNotWritable() {
    $dir = mockDir($this, $this->driver, $this->readdir);
    $this->assertFalse($this->driver->isWritable($dir));
  }
  
  public function testSize() {
    $file = mockFile($this, $this->driver, $this->filename);
    $this->assertEquals($this->filelen, $this->driver->size($file));
  }
  
  public function testSizeFileDoesntExist() {
    $this->setExpectedException(PathNotFoundException::class);
    
    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    $this->driver->size($file);
  }
}
