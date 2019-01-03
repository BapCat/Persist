<?php declare(strict_types=1);

require_once __DIR__ . '/_mocks.php';

use BapCat\Persist\PathNotFoundException;
use PHPUnit\Framework\TestCase;

class FileWriterTest extends TestCase {
  public function testFileDoesntExist(): void {
    $this->expectException(PathNotFoundException::class);

    $driver = mockDriver($this, false);
    $file = mockFile($this, $driver, '');

    mockFileWriter($this, $file);
  }

  public function testProperties(): void {
    $driver = mockDriver($this);
    $file = mockFile($this, $driver, '');

    $out = mockFileWriter($this, $file);

    static::assertEquals($file, $out->file);
  }

  public function testWrite(): void {
    $driver = mockDriver($this);
    $file = mockFile($this, $driver, '');

    $out = mockFileWriter($this, $file);

    static::assertEquals(10, $out->write('1234567890'));
  }
}
