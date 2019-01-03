<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/_mocks.php';

class DirectoryTest extends TestCase {
  public function testProperties(): void {
    $dirname = 'test';
    $driver = mockDriver($this);
    $dir = mockDir($this, $driver, $dirname);

    static::assertEquals(['a', 'b'], $dir->children);
  }

  public function testGetChildren(): void {
    $dirname = 'test';
    $driver = mockDirDriver($this, $dirname);
    $dir = mockDir($this, $driver, $dirname);

    static::assertSame(['a', 'b'], $dir->children);
  }

  public function testItrChildren(): void {
    $dirname = 'test';
    $driver = mockDirDriver($this, $dirname);
    $dir = mockDir($this, $driver, $dirname);

    $files = [];

    foreach($dir->children as $child) {
      $files[] = $child;
    }

    static::assertSame(['a', 'b'], $files);
  }

  public function testGetChild(): void {
    $dirname = 'test';
    $driver = mockDirDriver($this, $dirname);
    $dir = mockDir($this, $driver, $dirname);

    static::assertEquals('test', $dir->child['test']->name);

    $driver = mockFileDriver($this, $dirname);
    $dir = mockDir($this, $driver, $dirname);

    static::assertEquals('test', $dir->child['test']->name);
  }
}
