<?php

use BapCat\Persist\Directory;
use BapCat\Persist\Driver;
use BapCat\Persist\File;
use BapCat\Persist\Drivers\Local\LocalDirectory;
use BapCat\Persist\Drivers\Local\LocalFile;

function mockDriver(PHPUnit_Framework_TestCase $testcase, $root) {
  $driver = $testcase->getMockForAbstractClass(LocalDriver::class, [$root]);
  
  $driver
    ->method('instantiateFile')
    ->will($testcase->returnCallback(function($path) use($driver) {
      if(is_dir($path)) {
        return new LocalDirectory($driver, $path);
      }
      
      return new LocalFile($driver, $path);
    }))
  ;
  
  return $driver;
}

function mockFile(PHPUnit_Framework_TestCase $testcase, Driver $driver, $filename) {
  $file = $testcase->getMockForAbstractClass(File::class, [$driver, $filename]);
  return $file;
}

function mockDir(PHPUnit_Framework_TestCase $testcase, Driver $driver, $filename) {
  $dir = $testcase->getMockForAbstractClass(Directory::class, [$driver, $filename]);
  return $dir;
}
