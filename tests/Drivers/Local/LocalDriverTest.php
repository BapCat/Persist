<?php declare(strict_types=1);

require_once __DIR__ . '/_mocks.php';
require_once __DIR__ . '/FileCreatorTrait.php';

use BapCat\Persist\Directory;
use BapCat\Persist\File;
use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\NotADirectoryException;
use BapCat\Persist\NotAFileException;
use BapCat\Persist\PathNotFoundException;
use PHPUnit\Framework\TestCase;

class LocalDriverTest extends TestCase {
  use FileCreatorTrait;

  /** @var  LocalDriver  $driver */
  private $driver;

  public function setUp(): void {
    parent::setUp();
    $this->createTestFiles();
    $this->driver = new LocalDriver($this->datadir);
  }

  public function tearDown(): void {
    parent::tearDown();
    $this->deleteTestFiles();
  }

  public function testGetFile(): void {
    $file = $this->driver->getFile($this->filename);
    static::assertInstanceOf(File::class, $file);
  }

  public function testGetFileOnDirectory(): void {
    $this->expectException(NotAFileException::class);
    $this->driver->getFile($this->dirname);
  }

  public function testGetDirectory(): void {
    $dir = $this->driver->getDirectory($this->dirname);
    static::assertInstanceOf(Directory::class, $dir);
  }

  public function testGetDirectoryOnFile(): void {
    $this->expectException(NotADirectoryException::class);
    $this->driver->getDirectory($this->filename);
  }

  public function testIsFile(): void {
    static::assertTrue ($this->driver->isFile($this->filename));
    static::assertFalse($this->driver->isFile($this->dirname));
  }

  public function testIsDir(): void {
    static::assertTrue ($this->driver->isDir($this->dirname));
    static::assertFalse($this->driver->isDir($this->filename));
  }

  public function testFileExists(): void {
    $file = mockFile($this, $this->driver, $this->filename);
    static::assertTrue($this->driver->exists($file));
  }

  public function testDirExists(): void {
    $dir = mockDir($this, $this->driver, $this->dirname);
    static::assertTrue($this->driver->exists($dir));
  }

  public function testDoesntExist(): void {
    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    static::assertFalse($this->driver->exists($file));
  }

  public function testFileIsLink(): void {
    $file = mockFile($this, $this->driver, $this->linkname);
    static::assertTrue($this->driver->isLink($file));
  }

  public function testFileIsNotLink(): void {
    $file = mockFile($this, $this->driver, $this->filename);
    static::assertFalse($this->driver->isLink($file));
  }

  public function testDirIsLink(): void {
    //@TODO: need to figure out why unlink(...) doesn't work on directory symlinks on Windows
    //$dir = mockDir(...)
  }

  public function testDirIsNotLink(): void {
    $dir = mockDir($this, $this->driver, $this->dirname);
    static::assertFalse($this->driver->isLink($dir));
  }

  public function testIsLinkPathDoesntExist(): void {
    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    static::assertFalse($this->driver->isLink($file));
  }

  public function testFileIsReadable(): void {
    $file = mockFile($this, $this->driver, $this->readonly);
    static::assertTrue($this->driver->isReadable($file));
  }

  /**
   * @requires OS Linux
   */
  public function testFileIsNotReadable(): void {
    $file = mockFile($this, $this->driver, $this->writeonly);
    static::assertFalse($this->driver->isReadable($file));
  }

  public function testDirIsReadable(): void {
    $dir = mockDir($this, $this->driver, $this->readdir);
    static::assertTrue($this->driver->isReadable($dir));
  }

  /**
   * @requires OS Linux
   */
  public function testDirIsNotReadable(): void {
    $dir = mockDir($this, $this->driver, $this->writedir);
    static::assertFalse($this->driver->isReadable($dir));
  }

  public function testFileIsWritable(): void {
    $file = mockFile($this, $this->driver, $this->writeonly);
    static::assertTrue($this->driver->isWritable($file));
  }

  public function testFileIsNotWritable(): void {
    $file = mockFile($this, $this->driver, $this->readonly);
    static::assertFalse($this->driver->isWritable($file));
  }

  public function testDirIsWritable(): void {
    $dir = mockDir($this, $this->driver, $this->writedir);
    static::assertTrue($this->driver->isWritable($dir));
  }

  /**
   * @requires OS Linux
   */
  public function testDirIsNotWritable(): void {
    $dir = mockDir($this, $this->driver, $this->readdir);
    static::assertFalse($this->driver->isWritable($dir));
  }

  public function testSize(): void {
    $file = mockFile($this, $this->driver, $this->filename);
    static::assertEquals($this->filelen, $this->driver->size($file));
  }

  public function testSizeFileDoesntExist(): void {
    $this->expectException(PathNotFoundException::class);

    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    $this->driver->size($file);
  }

  public function testModified(): void {
    $file = mockFile($this, $this->driver, $this->filename);
    static::assertIsInt($this->driver->modified($file));
  }

  public function testModifiedFileDoesntExist(): void {
    $this->expectException(PathNotFoundException::class);

    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    $this->driver->modified($file);
  }

  public function testCreateFile(): void {
    $filename = $this->filename . '-test-create-from-driver';
    $this->driver->createFile($filename);

    static::assertTrue(is_file($filename));
  }

  public function testCreateDirectory(): void {
    $path = 'test-create-from-driver';
    $dir = $this->driver->createDirectory($path);
    static::assertDirectoryExists($dir->full_path);
  }

}
