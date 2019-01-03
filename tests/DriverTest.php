<?php declare(strict_types=1);

require_once __DIR__ . '/_mocks.php';

use BapCat\Persist\File;
use BapCat\Persist\Directory;
use PHPUnit\Framework\TestCase;

class DriverTest extends TestCase {
  public function testGetFile(): void {
    $filename = 'test';
    $driver = mockFileDriver($this, $filename);

    $dir = $driver->getFile($filename);

    static::assertInstanceOf(File::class, $dir);
  }

  public function testGetDirectory(): void {
    $dirname = 'test';
    $driver = mockDirDriver($this, $dirname);

    $dir = $driver->getDirectory($dirname);

    static::assertInstanceOf(Directory::class, $dir);
  }
}
