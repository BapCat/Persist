<?php declare(strict_types=1);

require_once __DIR__ . '/_mocks.php';
require_once __DIR__ . '/FileCreatorTrait.php';

use BapCat\Persist\Directory;
use BapCat\Persist\File;
use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\NotADirectoryException;
use BapCat\Persist\NotAFileException;
use BapCat\Persist\PathNotFoundException;
use PHPUnit\Framework\Assert;
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
    Assert::assertInstanceOf(File::class, $file);
  }

  public function testGetFileOnDirectory(): void {
    $this->expectException(NotAFileException::class);
    $this->driver->getFile($this->dirname);
  }

  public function testGetDirectory(): void {
    $dir = $this->driver->getDirectory($this->dirname);
    Assert::assertInstanceOf(Directory::class, $dir);
  }

  public function testGetDirectoryOnFile(): void {
    $this->expectException(NotADirectoryException::class);
    $this->driver->getDirectory($this->filename);
  }

  public function testIsFile(): void {
    Assert::assertTrue ($this->driver->isFile($this->filename));
    Assert::assertFalse($this->driver->isFile($this->dirname));
  }

  public function testIsDir(): void {
    Assert::assertTrue ($this->driver->isDir($this->dirname));
    Assert::assertFalse($this->driver->isDir($this->filename));
  }

  public function testFileExists(): void {
    $file = mockFile($this, $this->driver, $this->filename);
    Assert::assertTrue($this->driver->exists($file));
  }

  public function testDirExists(): void {
    $dir = mockDir($this, $this->driver, $this->dirname);
    Assert::assertTrue($this->driver->exists($dir));
  }

  public function testDoesntExist(): void {
    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    Assert::assertFalse($this->driver->exists($file));
  }

  public function testFileIsLink(): void {
    $file = mockFile($this, $this->driver, $this->linkname);
    Assert::assertTrue($this->driver->isLink($file));
  }

  public function testFileIsNotLink(): void {
    $file = mockFile($this, $this->driver, $this->filename);
    Assert::assertFalse($this->driver->isLink($file));
  }

  public function testDirIsLink(): void {
    //@TODO: need to figure out why unlink(...) doesn't work on directory symlinks on Windows
    //$dir = mockDir(...)

    Assert::assertTrue(true);
  }

  public function testDirIsNotLink(): void {
    $dir = mockDir($this, $this->driver, $this->dirname);
    Assert::assertFalse($this->driver->isLink($dir));
  }

  public function testIsLinkPathDoesntExist(): void {
    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    Assert::assertFalse($this->driver->isLink($file));
  }

  public function testFileIsReadable(): void {
    $file = mockFile($this, $this->driver, $this->readonly);
    Assert::assertTrue($this->driver->isReadable($file));
  }

  /**
   * @requires OS Linux
   */
  public function testFileIsNotReadable(): void {
    $file = mockFile($this, $this->driver, $this->writeonly);
    Assert::assertFalse($this->driver->isReadable($file));
  }

  public function testDirIsReadable(): void {
    $dir = mockDir($this, $this->driver, $this->readdir);
    Assert::assertTrue($this->driver->isReadable($dir));
  }

  /**
   * @requires OS Linux
   */
  public function testDirIsNotReadable(): void {
    $dir = mockDir($this, $this->driver, $this->writedir);
    Assert::assertFalse($this->driver->isReadable($dir));
  }

  public function testFileIsWritable(): void {
    $file = mockFile($this, $this->driver, $this->writeonly);
    Assert::assertTrue($this->driver->isWritable($file));
  }

  public function testFileIsNotWritable(): void {
    $file = mockFile($this, $this->driver, $this->readonly);
    Assert::assertFalse($this->driver->isWritable($file));
  }

  public function testDirIsWritable(): void {
    $dir = mockDir($this, $this->driver, $this->writedir);
    Assert::assertTrue($this->driver->isWritable($dir));
  }

  /**
   * @requires OS Linux
   */
  public function testDirIsNotWritable(): void {
    $dir = mockDir($this, $this->driver, $this->readdir);
    Assert::assertFalse($this->driver->isWritable($dir));
  }

  public function testSize(): void {
    $file = mockFile($this, $this->driver, $this->filename);
    Assert::assertEquals($this->filelen, $this->driver->size($file));
  }

  public function testSizeFileDoesntExist(): void {
    $this->expectException(PathNotFoundException::class);

    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    $this->driver->size($file);
  }

  public function testModified(): void {
    $file = mockFile($this, $this->driver, $this->filename);
    Assert::assertIsInt($this->driver->modified($file));
  }

  public function testModifiedFileDoesntExist(): void {
    $this->expectException(PathNotFoundException::class);

    $file = mockFile($this, $this->driver, $this->filename . 'idontexist');
    $this->driver->modified($file);
  }

  public function testCreateFile(): void {
    $filename = $this->filename . '-test-create-from-driver';
    $this->driver->createFile($filename);

    Assert::assertTrue(is_file($filename));
  }

  public function testCreateDirectory(): void {
    $path = 'test-create-from-driver';
    $dir = $this->driver->createDirectory($path);
    Assert::assertDirectoryExists($dir->full_path);
  }

}
