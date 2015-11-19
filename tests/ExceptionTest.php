<?php

use BapCat\Persist\NotADirectoryException;
use BapCat\Persist\NotAFileException;
use BapCat\Persist\PathNotFoundException;

class ExceptionTest extends PHPUnit_Framework_TestCase {

  public function testNotADirectory() {
    $testPath = '/test';
    $notADirectory = new NotADirectoryException($testPath);
    $this->assertEquals($testPath, $notADirectory->getPath());
  }
  
  public function testNotAFile() {
    $testPath = '/test';
    $notADirectory = new NotAFileException($testPath);
    $this->assertEquals($testPath, $notADirectory->getPath());
  }
  
  public function testPathNotFound() {
    $testPath = '/test';
    $notADirectory = new PathNotFoundException($testPath);
    $this->assertEquals($testPath, $notADirectory->getPath());
  }


}