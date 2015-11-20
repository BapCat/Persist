<?php

use BapCat\Persist\PathAlreadyExistsException;
use BapCat\Persist\NotADirectoryException;
use BapCat\Persist\NotAFileException;
use BapCat\Persist\PathNotFoundException;

class ExceptionTest extends PHPUnit_Framework_TestCase {

  public function setUp() {
    $this->path = '/test';
  }

  public function testNotADirectory() {
    $notADirectory = new NotADirectoryException($this->path);
    $this->assertEquals($this->path, $notADirectory->getPath());
  }
  
  public function testNotAFile() {
    $notADirectory = new NotAFileException($this->path);
    $this->assertEquals($this->path, $notADirectory->getPath());
  }
  
  public function testPathNotFound() {
    $notADirectory = new PathNotFoundException($this->path);
    $this->assertEquals($this->path, $notADirectory->getPath());
  }
  
  public function testPathAlreadyExists() {
    $notADirectory = new PathAlreadyExistsException($this->path);
    $this->assertEquals($this->path, $notADirectory->getPath());
  }

}