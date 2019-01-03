<?php declare(strict_types=1);

use BapCat\Persist\Drivers\Local\LocalDirectory;
use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;
use PHPUnit\Framework\TestCase;

function mockLocalDriver(TestCase $testcase, string $root): LocalDriver {
  $driver = $testcase->getMockBuilder(LocalDriver::class)
    ->setConstructorArgs([$root])
    ->setMethods(['instantiateFile', 'instantiateDir'])
    ->getMockForAbstractClass()
  ;

  $driver
    ->method('instantiateFile')
    ->will(TestCase::returnCallback(function($path) use($driver) {
      return new LocalFile($driver, $path);
    }))
  ;

  $driver
    ->method('instantiateDir')
    ->will(TestCase::returnCallback(function($path) use($driver) {
      return new LocalDirectory($driver, $path);
    }))
  ;

  return $driver;
}
