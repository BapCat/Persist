<?php

use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;
use BapCat\Persist\Drivers\Local\LocalFileReader;

class LocalFileReaderTest extends PHPUnit_Framework_TestCase {
  private $file;
  private $file_reader;
  private $file_ptr;
  
  public function setUp() {
    $dir  = __DIR__ . '/storage';
    $path = '/filereadertest';
    $full_path = $dir . $path;
    
    file_put_contents($full_path, 'This is a test');
    
    $driver = $this
      ->getMockBuilder(LocalDriver::class)
      ->setConstructorArgs([$dir])
      ->getMock()
    ;
    
    $this->file = $this
      ->getMockBuilder(LocalFile::class)
      ->setConstructorArgs([$driver, $path])
      ->setMethods(['getFullPath', 'getExists'])
      ->getMock()
    ;
    
    $this->file
      ->method('getFullPath')
      ->willReturn($full_path)
    ;
    
    $this->file
      ->method('getExists')
      ->willReturn(true)
    ;
    
    $this->file_ptr = fopen($full_path, 'r');
    $this->file_reader = new LocalFileReader($this->file, $this->file_ptr);
  }
  
  public function tearDown() {
    fclose($this->file_ptr);
  }
  
  public function testGetFile() {
    $this->assertSame($this->file, $this->file_reader->file);
  }
  
  public function testGetLength() {
    $this->assertSame(14, $this->file_reader->length);
  }
  
  public function testGetRemaining() {
    $this->assertSame(14, $this->file_reader->length);
  }
  
  public function testGetHasMore() {
    $this->assertTrue($this->file_reader->has_more);
  }
  
  public function testRead() {
    $this->assertSame(14, $this->file_reader->length);
    $this->assertSame(14, $this->file_reader->remaining);
    $this->assertTrue($this->file_reader->has_more);
    
    $this->assertSame('This is', $this->file_reader->read(7));

    $this->assertSame(14, $this->file_reader->length);
    $this->assertSame(7, $this->file_reader->remaining);
    $this->assertTrue($this->file_reader->has_more);
    
    $this->assertSame(' a test', $this->file_reader->read(7));
    
    $this->assertSame(14, $this->file_reader->length);
    $this->assertSame(0, $this->file_reader->remaining);
    $this->assertFalse($this->file_reader->has_more);
  }
}
