<?php declare(strict_types=1);

use BapCat\Persist\PathAlreadyExistsException;
use BapCat\Persist\NotADirectoryException;
use BapCat\Persist\NotAFileException;
use BapCat\Persist\PathNotFoundException;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase {
  /** @var  string  $path */
  private $path;

  public function setUp(): void {
    parent::setUp();
    $this->path = '/test';
  }

  public function testNotADirectory(): void {
    $notADirectory = new NotADirectoryException($this->path);
    static::assertEquals($this->path, $notADirectory->getPath());
  }

  public function testNotAFile(): void {
    $notADirectory = new NotAFileException($this->path);
    static::assertEquals($this->path, $notADirectory->getPath());
  }

  public function testPathNotFound(): void {
    $notADirectory = new PathNotFoundException($this->path);
    static::assertEquals($this->path, $notADirectory->getPath());
  }

  public function testPathAlreadyExists(): void {
    $notADirectory = new PathAlreadyExistsException($this->path);
    static::assertEquals($this->path, $notADirectory->getPath());
  }

}
