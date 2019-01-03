<?php declare(strict_types=1);

require_once __DIR__ . '/_mocks.php';
require_once __DIR__ . '/FileCreatorTrait.php';

use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;
use BapCat\Persist\PathAlreadyExistsException;
use BapCat\Persist\Drivers\Local\LocalFileReader;
use BapCat\Persist\Drivers\Local\LocalFileWriter;
use PHPUnit\Framework\TestCase;

class LocalFileTest extends TestCase {
  use FileCreatorTrait;

  /** @var  LocalDriver  $driver */
  private $driver;

  public function setUp(): void {
    parent::setUp();
    $this->createTestFiles();
    $this->driver = mockLocalDriver($this, $this->datadir);
  }

  public function tearDown(): void {
    parent::tearDown();
    $this->deleteTestFiles();
  }

  public function testConstruct(): void {
    $localFile = new LocalFile($this->driver, $this->filename);
    static::assertEquals($localFile, $localFile->makeLocal());
    static::assertEquals($localFile->full_path, $this->driver->getFullPath($this->filename));
  }

  public function testAlreadyExists(): void {
    // NOTE: Comes from FileCreatorTrait
    $localFile = new LocalFile($this->driver, $this->filename);
    $this->expectException(PathAlreadyExistsException::class);
    $localFile->create();
  }

  public function testRead(): void {
    $filename = "{$this->filename}-new-read";
    touch($filename);

    $file = new LocalFile($this->driver, $filename);

    $read = false;
    $file->read(function(LocalFileReader $reader) use(&$read) {
      $read = true;
    });

    static::assertTrue($read);
  }

  public function testReadAll(): void {
    $filename = "{$this->filename}-readall";
    $contents = 'this is a test';

    file_put_contents($filename, $contents);

    $file = new LocalFile($this->driver, $filename);

    static::assertSame($contents, $file->readAll());
  }

  /**
   * @expectedException Exception
   */
  public function testReadAllFailure(): void {
    $filename = "{$this->filename}-readall-nope";

    $file = new LocalFile($this->driver, $filename);
    $file->readAll();
  }

  public function testWrite(): void {
    $filename = "{$this->filename}-new-write";
    touch($filename);

    $file = new LocalFile($this->driver, $filename);

    $written = false;
    $file->write(function(LocalFileWriter $reader) use(&$written) {
      $written = true;
    });

    static::assertTrue($written);
  }

  public function testWriteAll(): void {
    $filename = "{$this->filename}-writeall";
    $contents = 'this is a test';

    $file = new LocalFile($this->driver, $filename);
    $file->writeAll($contents);

    static::assertSame($contents, file_get_contents($filename));
  }

  /**
   * @expectedException Exception
   */
  public function testWriteAllFailure(): void {
    $filename = "dirdoesnotexist/{$this->filename}-writeall";
    $contents = 'this is a test';

    $file = new LocalFile($this->driver, $filename);
    $file->writeAll($contents);
  }

  public function testCreate(): void {
    $localFile = new LocalFile($this->driver, "{$this->filename}-new-create");
    $localFile->create();
    static::assertTrue(true);
  }

  public function testMove(): void {
    $to_move = new LocalFile($this->driver, "{$this->filename}-to-move");
    $moved   = new LocalFile($this->driver, "{$this->filename}-moved");

    static::assertFalse($moved->exists);

    $to_move->create();

    static::assertTrue($to_move->move($moved));

    static::assertFalse($to_move->exists);
    static::assertTrue($moved->exists);
  }

  public function testCopy(): void {
    $to_copy = new LocalFile($this->driver, "{$this->filename}-to-copy");
    $copied  = new LocalFile($this->driver, "{$this->filename}-copied");

    static::assertFalse($copied->exists);

    $to_copy->create();

    static::assertTrue($to_copy->copy($copied));

    static::assertTrue($to_copy->exists);
    static::assertTrue($copied->exists);
  }

  public function testDelete(): void {
    $filename = "{$this->filename}-new-delete";
    touch($filename);

    $file = new LocalFile($this->driver, $filename);

    static::assertTrue($file->delete());
    static::assertFileNotExists($file->full_path);
  }
}
