<?php declare(strict_types=1);

use BapCat\Persist\Directory;
use BapCat\Persist\Driver;
use BapCat\Persist\File;
use BapCat\Persist\Path;
use BapCat\Persist\FileReader;
use BapCat\Persist\FileWriter;
use PHPUnit\Framework\TestCase;

function mockFileDriver(TestCase $testcase, string $filename): Driver {
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

function mockDirDriver(TestCase $testcase, string $filename): Driver {
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

function mockDriver(TestCase $testcase, bool $exists = true): Driver {
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

function mockPath(TestCase $testcase, Driver $driver, string $filename): Path {
  return $testcase->getMockBuilder(Path::class)->setConstructorArgs([$driver, $filename])->getMockForAbstractClass();
}

function mockFile(TestCase $testcase, Driver $driver, string $filename): File {
  return $testcase->getMockBuilder(File::class)->setConstructorArgs([$driver, $filename])->getMockForAbstractClass();
}

function mockDir(TestCase $testcase, Driver $driver, string $filename): Directory {
  $dir = $testcase->getMockBuilder(Directory::class)->setConstructorArgs([$driver, $filename])->getMockForAbstractClass();

  $dir
    ->method('loadChildren')
    ->willReturn(['a', 'b']);

  return $dir;
}

function mockFileReader(TestCase $testcase, File $file, int $length): FileReader {
  $in = $testcase->getMockBuilder(FileReader::class)->setConstructorArgs([$file])->getMockForAbstractClass();

  $remaining = $length;

  $in
    ->method('getHasMore')
    ->will(TestCase::returnCallback(function() use(&$remaining) {
      return $remaining > 0;
    }));

  $in
    ->method('read')
    ->will(TestCase::returnCallback(function(int $length = 0) use(&$remaining) {
      $remaining -= $length;
      return random_bytes($length);
    }));

  return $in;
}

function mockFileWriter(TestCase $testcase, File $file): FileWriter {
  $in = $testcase->getMockBuilder(FileWriter::class)->setConstructorArgs([$file])->getMockForAbstractClass();

  $in
    ->method('write')
    ->will(TestCase::returnCallback(function($data) {
      return strlen($data);
    }));

  return $in;
}
