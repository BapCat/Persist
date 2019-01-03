<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/_mocks.php';

class PathTest extends TestCase {
  public function testProperties(): void {
    $driver = mockDriver($this);
    $filename = 'test/a';

    $path = mockPath($this, $driver, $filename);

    static::assertEquals($driver, $path->driver);
    static::assertEquals($filename, $path->path);
    static::assertEquals('a', $path->name);
    static::assertTrue($path->exists);
    static::assertTrue($path->is_link);
    static::assertTrue($path->is_readable);
    static::assertTrue($path->is_writable);
    static::assertSame(0, $path->modified);
  }
}
