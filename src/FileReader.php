<?php declare(strict_types=1); namespace BapCat\Persist;

use BapCat\Propifier\PropifierTrait;

/**
 * Defines a class capable of reading from a file
 *
 * @property-read  File  $file       The file to read
 * @property-read  int   $length     The length of the file
 * @property-read  int   $remaining  How much is left to be read
 * @property-read  bool  $has_more   Is there more data to read?
 */
abstract class FileReader {
  use PropifierTrait;

  /** @var  File  $file */
  private $file;

  /**
   * @param  File  $file  The file to read from
   *
   * @throws  PathNotFoundException
   */
  public function __construct(File $file) {
    if(!$file->exists) {
      throw new PathNotFoundException($file->path);
    }

    $this->file = $file;
  }

  /**
   * @return  File
   */
  protected function getFile(): File {
    return $this->file;
  }

  /**
   * @return  int
   *
   * @throws  FileReadException
   */
  protected abstract function getLength(): int;

  /**
   * @return  int
   */
  protected abstract function getRemaining(): int;

  /**
   * @return  bool
   */
  protected abstract function getHasMore(): bool;

  /**
   * Reads an arbitrary block of data from the file
   *
   * @param  int  $length  The amount of data to read from the file
   *
   * @return  string  The data read from the file
   *
   * @throws  FileReadException
   */
  public abstract function read(int $length = 0): string;
}
