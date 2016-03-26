<?php

use BapCat\Persist\Driver;
use BapCat\Persist\Drivers\Local\LocalDirectory;
use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;

function mockLocalDriver(PHPUnit_Framework_TestCase $testcase, $root) {
  $driver = $testcase->getMockBuilder(LocalDriver::class)
    ->setConstructorArgs([$root])
    ->setMethods(['instantiateFile', 'instantiateDir'])
    ->getMockForAbstractClass()
  ;
  
  $driver
    ->method('instantiateFile')
    ->will($testcase->returnCallback(function($path) use($driver) {
      return new LocalFile($driver, $path);
    }))
  ;
  
  $driver
    ->method('instantiateDir')
    ->will($testcase->returnCallback(function($path) use($driver) {
      return new LocalDirectory($driver, $path);
    }))
  ;
  
  return $driver;
}
