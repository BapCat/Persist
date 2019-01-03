<?php declare(strict_types=1);

require_once __DIR__ . '/_mocks.php';

use BapCat\Persist\PathNotFoundException;
use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase {
  public function testFileDoesntExist(): void {
    $this->expectException(PathNotFoundException::class);

    $driver = mockDriver($this, false);
    $file = mockFile($this, $driver, '');

    mockFileReader($this, $file, 100);
  }

  public function testProperties(): void {
    $driver = mockDriver($this);
    $file = mockFile($this, $driver, '');

    $in = mockFileReader($this, $file, 100);

    static::assertEquals($file, $in->file);
    static::assertTrue($in->has_more);

    $in = mockFileReader($this, $file, 0);
    static::assertFalse($in->has_more);
  }

  public function testRead(): void {
    $driver = mockDriver($this);
    $file = mockFile($this, $driver, '');

    $in = mockFileReader($this, $file, 100);

    static::assertTrue($in->has_more);
    static::assertEquals(50, strlen($in->read(50)));
    static::assertTrue($in->has_more);
    static::assertEquals(50, strlen($in->read(50)));
    static::assertFalse($in->has_more);
  }
}
