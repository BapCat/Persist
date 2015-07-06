<?php

use BapCat\Interfaces\Persist\Driver;
use BapCat\Persist\Drivers\Filesystem\FilesystemDirectory;
use BapCat\Persist\Drivers\Filesystem\FilesystemFile;

function mockDriver(PHPUnit_Framework_TestCase $testcase, $root) {
  $driver = $testcase->getMockForAbstractClass('BapCat\Persist\Drivers\Filesystem\FilesystemDriver', [$root]);
  
  $driver
    ->method('instantiateFile')
    ->will($testcase->returnCallback(function($path) use($driver) {
      if(is_dir($path)) {
        return new FilesystemDirectory($driver, $path);
      }
      
      return new FilesystemFile($driver, $path);
    }));
  
  return $driver;
}

function mockFile(PHPUnit_Framework_TestCase $testcase, Driver $driver, $filename) {
  $file = $testcase->getMockForAbstractClass('BapCat\Interfaces\Persist\File', [$driver, $filename]);
  return $file;
}

function mockDir(PHPUnit_Framework_TestCase $testcase, Driver $driver, $filename) {
  $dir = $testcase->getMockForAbstractClass('BapCat\Interfaces\Persist\Directory', [$driver, $filename]);
  return $dir;
}
