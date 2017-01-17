<?php

require_once __DIR__ . '/FileCreatorTrait.php';
require_once __DIR__ . '/LocalMocksTrait.php';

use BapCat\Persist\Drivers\Local\LocalFile;
use BapCat\Persist\PathAlreadyExistsException;
use BapCat\Persist\Drivers\Local\LocalFileReader;
use BapCat\Persist\Drivers\Local\LocalFileWriter;

class LocalFileTest extends PHPUnit_Framework_TestCase {
  use FileCreatorTrait;
  use LocalMocksTrait;
  
  private $driver;
  
  public function setUp() {
    $this->createTestFiles();
    $this->driver = $this->mockLocalDriver($this->datadir);
  }
  
  public function tearDown() {
    $this->deleteTestFiles();
  }
  
  public function testConstruct() {
    $localFile = new LocalFile($this->driver, $this->filename);
    $this->assertEquals($localFile, $localFile->makeLocal());
    $this->assertEquals($localFile->full_path, $this->driver->getFullPath($this->filename));
  }
  
  public function testAlreadyExists() {
    // NOTE: Comes from FileCreatorTrait
    $localFile = new LocalFile($this->driver, $this->filename);
    $this->setExpectedException(PathAlreadyExistsException::class);
    $localFile->create();
  }
  
  public function testRead() {
    $filename = "{$this->filename}-new-read";
    touch($filename);
    
    $file = new LocalFile($this->driver, $filename);
    
    $read = false;
    $file->read(function(LocalFileReader $reader) use(&$read) {
      $read = true;
    });
    
    $this->assertTrue($read);
  }
  
  public function testReadAll() {
    $filename = "{$this->filename}-readall";
    $contents = 'this is a test';
    
    file_put_contents($filename, $contents);
    
    $file = new LocalFile($this->driver, $filename);
    
    $this->assertSame($contents, $file->readAll());
  }
  
  /**
   * @expectedException Exception
   */
  public function testReadAllFailure() {
    $filename = "{$this->filename}-readall-nope";
    
    $file = new LocalFile($this->driver, $filename);
    $file->readAll();
  }
  
  public function testWrite() {
    $filename = "{$this->filename}-new-write";
    touch($filename);
    
    $file = new LocalFile($this->driver, $filename);
    
    $written = false;
    $file->write(function(LocalFileWriter $reader) use(&$written) {
      $written = true;
    });
    
    $this->assertTrue($written);
  }
  
  public function testWriteAll() {
    $filename = "{$this->filename}-writeall";
    $contents = 'this is a test';
    
    $file = new LocalFile($this->driver, $filename);
    $file->writeAll($contents);
    
    $this->assertSame($contents, file_get_contents($filename));
  }
  
  /**
   * @expectedException Exception
   */
  public function testWriteAllFailure() {
    $filename = "dirdoesnotexist/{$this->filename}-writeall";
    $contents = 'this is a test';
    
    $file = new LocalFile($this->driver, $filename);
    $file->writeAll($contents);
  }
  
  public function testCreate() {
    $localFile = new LocalFile($this->driver, "{$this->filename}-new-create");
    $localFile->create();
  }
  
  public function testMove() {
    $to_move = new LocalFile($this->driver, "{$this->filename}-to-move");
    $moved   = new LocalFile($this->driver, "{$this->filename}-moved");
    
    $this->assertFalse($moved->exists);
    
    $to_move->create();
    
    $this->assertTrue($to_move->move($moved));
    
    $this->assertFalse($to_move->exists);
    $this->assertTrue($moved->exists);
  }
  
  public function testCopy() {
    $to_copy = new LocalFile($this->driver, "{$this->filename}-to-copy");
    $copied  = new LocalFile($this->driver, "{$this->filename}-copied");
    
    $this->assertFalse($copied->exists);
    
    $to_copy->create();
    
    $this->assertTrue($to_copy->copy($copied));
    
    $this->assertTrue($to_copy->exists);
    $this->assertTrue($copied->exists);
  }
  
  public function testDelete() {
    $filename = "{$this->filename}-new-delete";
    touch($filename);
    
    $file = new LocalFile($this->driver, $filename);
    
    $this->assertTrue($file->delete());
    $this->assertFalse(file_exists($file->full_path));
  }
}
