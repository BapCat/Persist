<?php

use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;
use BapCat\Persist\Drivers\Local\LocalFileWriter;

class LocalFileWriterTest extends PHPUnit_Framework_TestCase {
  private $file;
  private $file_writer;
  private $file_ptr;
  
  public function setUp() {
    $dir  = __DIR__ . '/storage';
    $path = '/filewritertest';
    $full_path = $dir . $path;
    
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
    
    $this->file_ptr = fopen($full_path, 'w');
    $this->file_writer = new LocalFileWriter($this->file, $this->file_ptr);
  }
  
  public function tearDown() {
    fclose($this->file_ptr);
  }
  
  public function testGetFile() {
    $this->assertSame($this->file, $this->file_writer->file);
  }
  
  public function testWrite() {
    $this->file_writer->write('this is a test');
    
    $this->assertSame('this is a test', file_get_contents($this->file->full_path));
  }
}
