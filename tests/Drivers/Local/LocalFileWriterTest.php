<?php declare(strict_types=1);

use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\Drivers\Local\LocalFile;
use BapCat\Persist\Drivers\Local\LocalFileWriter;
use PHPUnit\Framework\TestCase;

class LocalFileWriterTest extends TestCase {
  /** @var  LocalFile  $file */
  private $file;

  /** @var  LocalFileWriter  $file_writer */
  private $file_writer;

  /** @var  resource  $file_ptr */
  private $file_ptr;

  public function setUp(): void {
    parent::setUp();

    $dir  = __DIR__ . '/storage';
    $path = '/filewritertest';
    $full_path = $dir . $path;

    $driver = $this
      ->getMockBuilder(LocalDriver::class)
      ->setConstructorArgs([$dir])
      ->getMock()
    ;

    $this->file = $this
      ->getMockBuilder(LocalFile::class)
      ->setConstructorArgs([$driver, $path])
      ->setMethods(['getFullPath', 'getExists'])
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

    $this->file_ptr = fopen($full_path, 'wb');
    $this->file_writer = new LocalFileWriter($this->file, $this->file_ptr);
  }

  public function tearDown(): void {
    parent::tearDown();
    fclose($this->file_ptr);
  }

  public function testGetFile(): void {
    static::assertSame($this->file, $this->file_writer->file);
  }

  public function testWrite(): void {
    $this->file_writer->write('this is a test');

    static::assertSame('this is a test', file_get_contents($this->file->full_path));
  }
}
