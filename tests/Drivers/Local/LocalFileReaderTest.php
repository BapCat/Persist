<?php

use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;
use BapCat\Persist\Drivers\Local\LocalFileReader;

class LocalFileReaderTest extends PHPUnit_Framework_TestCase {
  private $file;
  private $file_reader;
  private $file_ptr;
  
  public function setUp() {
    $driver = $this
      ->getMockBuilder(LocalDriver::class)
      ->setConstructorArgs([__DIR__])
      ->setMethods()
      ->getMock()
    ;
    
    $this->file = $this
      ->getMockBuilder(LocalFile::class)
      ->setConstructorArgs([$driver, '/data/filereader'])
      ->setMethods(['getFullPath'])
      ->getMock()
    ;
    
    $this->file
      ->method('getFullPath')
      ->willReturn($path)
    ;
    
    $this->file_ptr = fopen($path, 'r');
    $this->file_reader = new LocalFileReader($this->file, $this->file_ptr);
  }
  
  public function tearDown() {
    fclose($this->file_ptr);
  }
  
  public function testGetFile() {
    $this->assertSame($this->file, $this->file_reader->file);
  }
  
  public function testGetLength() {
    $this->assertSame(15, $this->file_reader->length);
  }
  
  public function testGetRemaining() {
    $this->assertSame(15, $this->file_reader->length);
  }
  
  public function testGetHasMore() {
    $this->assertTrue($this->file_reader->has_more);
  }
  
  public function testRead() {
    $this->assertSame(15, $this->file_reader->length);
    $this->assertSame(15, $this->file_reader->length);
    $this->assertTrue($this->file_reader->has_more);
    $this->assertSame('This is', $this->file_reader->read(7));
    $this->assertSame(8, $this->file_reader->remaining);
    $this->assertTrue($this->file_reader->has_more);
    $this->assertSame(' a test', $this->file_reader->read(7));
    $this->assertSame(0, $this->file_reader->remaining);
    $this->assertFalse($this->file_reader->has_more);
  }
}
