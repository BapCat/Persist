<?php declare(strict_types=1);

use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;
use BapCat\Persist\Drivers\Local\LocalFileReader;
use PHPUnit\Framework\TestCase;

class LocalFileReaderTest extends TestCase {
  /** @var  LocalFile  $file */
  private $file;

  /** @var  LocalFileReader  $file_reader */
  private $file_reader;

  /** @var  resource  $file_ptr */
  private $file_ptr;

  public function setUp(): void {
    parent::setUp();

    $dir  = __DIR__ . '/storage';
    $path = '/filereadertest';
    $full_path = $dir . $path;

    file_put_contents($full_path, 'This is a test');

    $driver = $this
      ->getMockBuilder(LocalDriver::class)
      ->setConstructorArgs([$dir])
      ->getMock()
    ;

    $this->file = $this
      ->getMockBuilder(LocalFile::class)
      ->setConstructorArgs([$driver, $path])
      ->onlyMethods(['getFullPath', 'getExists'])
      ->getMock()
    ;

    $this->file
      ->method('getFullPath')
      ->willReturn($full_path)
    ;

    $this->file
      ->method('getExists')
      ->willReturn(true)
    ;

    $this->file_ptr = fopen($full_path, 'rb');
    $this->file_reader = new LocalFileReader($this->file, $this->file_ptr);
  }

  public function tearDown(): void {
    parent::tearDown();
    fclose($this->file_ptr);
  }

  public function testGetFile(): void {
    static::assertSame($this->file, $this->file_reader->file);
  }

  public function testGetLength(): void {
    static::assertSame(14, $this->file_reader->length);
  }

  public function testGetRemaining(): void {
    static::assertSame(14, $this->file_reader->length);
  }

  public function testGetHasMore(): void {
    static::assertTrue($this->file_reader->has_more);
  }

  public function testRead(): void {
    static::assertSame(14, $this->file_reader->length);
    static::assertSame(14, $this->file_reader->remaining);
    static::assertTrue($this->file_reader->has_more);

    static::assertSame('This is', $this->file_reader->read(7));

    static::assertSame(14, $this->file_reader->length);
    static::assertSame(7, $this->file_reader->remaining);
    static::assertTrue($this->file_reader->has_more);

    static::assertSame(' a test', $this->file_reader->read(7));

    static::assertSame(14, $this->file_reader->length);
    static::assertSame(0, $this->file_reader->remaining);
    static::assertFalse($this->file_reader->has_more);
  }

  public function testReadAll(): void {
    static::assertSame('This is a test', $this->file_reader->read());
  }
}
