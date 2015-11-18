<?php

use BapCat\Persist\Directory;
use BapCat\Persist\Driver;
use BapCat\Persist\File;
use BapCat\Persist\Path;
use BapCat\Persist\FileInputStream;
use BapCat\Persist\FileOutputStream;

function mockFileDriver(PHPUnit_Framework_TestCase $testcase, $filename) {
  $driver = mockDriver($testcase);
  $file = mockFile($testcase, $driver, $filename);
  
  $driver
    ->method('isFile')
    ->willReturn(true);
  
  $driver
    ->method('isDir')
    ->willReturn(false);
  
  $driver
    ->method('instantiateFile')
    ->willReturn($file);
  
  return $driver;
}

function mockDirDriver(PHPUnit_Framework_TestCase $testcase, $filename) {
  $driver = mockDriver($testcase);
  $dir = mockDir($testcase, $driver, $filename);
  
  $driver
    ->method('isFile')
    ->willReturn(false);
  
  $driver
    ->method('isDir')
    ->willReturn(true);
  
  $driver
    ->method('instantiateDir')
    ->willReturn($dir);
  
  return $driver;
}

function mockDriver(PHPUnit_Framework_TestCase $testcase, $exists = true) {
  $driver = $testcase
    ->getMockBuilder(Driver::class)
    ->getMockForAbstractClass();
  
  $driver
    ->method('exists')
    ->willReturn($exists);
  
  $driver
    ->method('size')
    ->willReturn(100);
  
  $driver
    ->method('isLink')
    ->willReturn(true);
  
  $driver
    ->method('isReadable')
    ->willReturn(true);
  
  $driver
    ->method('isWritable')
    ->willReturn(true);
  
  $driver
    ->method('modified')
    ->willReturn(0);
  
  return $driver;
}

function mockPath(PHPUnit_Framework_TestCase $testcase, Driver $driver, $filename) {
  $path = $testcase->getMockForAbstractClass(Path::class, [$driver, $filename]);
  return $path;
}

function mockFile(PHPUnit_Framework_TestCase $testcase, Driver $driver, $filename) {
  $file = $testcase->getMockForAbstractClass(File::class, [$driver, $filename]);
  return $file;
}

function mockDir(PHPUnit_Framework_TestCase $testcase, Driver $driver, $filename) {
  $dir = $testcase->getMockForAbstractClass(Directory::class, [$driver, $filename]);
  
  $dir
    ->method('loadChildren')
    ->willReturn(['a', 'b']);
  
  return $dir;
}

function mockFileInputStream(PHPUnit_Framework_TestCase $testcase, File $file, $length) {
  $in = $testcase->getMockForAbstractClass(FileInputStream::class, [$file]);
  
  $remaining = $length;
  
  $in
    ->method('getHasMore')
    ->will($testcase->returnCallback(function() use(&$remaining) {
      return $remaining > 0;
    }));
  
  $in
    ->method('read')
    ->will($testcase->returnCallback(function($length = 0) use(&$remaining) {
      $remaining -= $length;
      return openssl_random_pseudo_bytes($length);
    }));
  
  return $in;
}

function mockFileOutputStream(PHPUnit_Framework_TestCase $testcase, File $file) {
  $in = $testcase->getMockForAbstractClass(FileOutputStream::class, [$file]);
  
  $in
    ->method('write')
    ->will($testcase->returnCallback(function($data) {
      return strlen($data);
    }));
  
  return $in;
}
