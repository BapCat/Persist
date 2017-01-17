<?php

use BapCat\Persist\Driver;
use BapCat\Persist\Drivers\Local\LocalDirectory;
use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;

trait LocalMocksTrait {
  public function mockLocalDriver($root) {
    $driver = $this->getMockBuilder(LocalDriver::class)
      ->setConstructorArgs([$root])
      ->setMethods(['instantiateFile', 'instantiateDir'])
      ->getMockForAbstractClass()
    ;
    
    $driver
      ->method('instantiateFile')
      ->will($this->returnCallback(function($path) use($driver) {
        return new LocalFile($driver, $path);
      }))
    ;
    
    $driver
      ->method('instantiateDir')
      ->will($this->returnCallback(function($path) use($driver) {
        return new LocalDirectory($driver, $path);
      }))
    ;
    
    return $driver;
  }
}
