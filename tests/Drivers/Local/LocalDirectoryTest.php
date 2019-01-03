<?php declare(strict_types=1);

require_once __DIR__ . '/_mocks.php';
require_once __DIR__ . '/FileCreatorTrait.php';

use BapCat\Persist\Drivers\Local\LocalDirectory;
use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;
use PHPUnit\Framework\TestCase;

class LocalDirectoryTest extends TestCase {
  use FileCreatorTrait;

  /** @var  LocalDriver  $driver */
  private $driver;

  public function setUp(): void {
    parent::setUp();
    $this->createTestFiles();
    $this->driver = mockLocalDriver($this, dirname($this->datadir));
  }

  public function tearDown(): void {
    parent::tearDown();
    $this->deleteTestFiles();
  }

  public function testLoadChildren(): void {
    $directory = new LocalDirectory($this->driver, basename($this->datadir));
    $children = $directory->children;
    $expected = $this->listFiles($this->datadir);

    foreach($children as $child) {
      static::assertContains($child->name, $expected);
    }
  }

  public function testMoveEmptyDir(): void {
    $to_move = new LocalDirectory($this->driver, basename($this->datadir) . '/to-move');
    $moved   = new LocalDirectory($this->driver, basename($this->datadir) . '/moved');

    static::assertFalse($moved->exists);

    $to_move->create();

    static::assertTrue($to_move->move($moved));

    static::assertFalse($to_move->exists);
    static::assertTrue($moved->exists);
  }

  public function testMoveNonEmptyDir(): void {
    $to_move = new LocalDirectory($this->driver, basename($this->datadir) . '/ne-to-move');
    $moved   = new LocalDirectory($this->driver, basename($this->datadir) . '/ne-moved');

    $child       = new LocalFile($this->driver, $to_move->path . '/child');
    $moved_child = new LocalFile($this->driver, $moved->path . '/child');

    static::assertFalse($moved->exists);

    $to_move->create();
    $child->create();

    static::assertTrue($to_move->move($moved));

    static::assertFalse($to_move->exists);
    static::assertTrue($moved->exists);
    static::assertFalse($child->exists);
    static::assertTrue($moved_child->exists);
  }

  public function testCopyEmptyDir(): void {
    $to_copy = new LocalDirectory($this->driver, basename($this->datadir) . '/to-copy');
    $copied  = new LocalDirectory($this->driver, basename($this->datadir) . '/copied');

    static::assertFalse($copied->exists);

    $to_copy->create();

    static::assertTrue($to_copy->copy($copied));

    static::assertTrue($to_copy->exists);
    static::assertTrue($copied->exists);
  }

  public function testCopyNonEmptyDir(): void {
    $to_copy = new LocalDirectory($this->driver, basename($this->datadir) . '/ne-to-copy');
    $copied  = new LocalDirectory($this->driver, basename($this->datadir) . '/ne-copied');

    $child       = new LocalFile($this->driver, $to_copy->path . '/child');
    $moved_child = new LocalFile($this->driver, $copied->path . '/child');

    $child_dir       = new LocalDirectory($this->driver, $to_copy->path . '/child-dir');
    $moved_child_dir = new LocalDirectory($this->driver, $copied->path . '/child-dir');

    static::assertFalse($copied->exists);

    $to_copy->create();
    $child->create();
    $child_dir->create();

    static::assertTrue($to_copy->copy($copied));

    static::assertTrue($to_copy->exists);
    static::assertTrue($copied->exists);
    static::assertTrue($child->exists);
    static::assertTrue($moved_child->exists);
    static::assertTrue($child_dir->exists);
    static::assertTrue($moved_child_dir->exists);
  }

  public function testDelete(): void {
    $directory = new LocalDirectory($this->driver, basename($this->datadir));

    // Make the read-only stuff readable so it can be deleted
    chmod($this->readonly,  0755);
    chmod($this->readdir,   0755);
    chmod($this->writeonly, 0755);
    chmod($this->writedir,  0755);

    static::assertTrue($directory->delete());
    static::assertFileNotExists($directory->full_path);

    // Recreate data dir so next tests pass
    $directory->create();
  }

  public function testGetFullPath(): void {
    $directory = new LocalDirectory($this->driver, basename($this->datadir));

    static::assertSame($this->datadir, $directory->full_path);
  }
}
