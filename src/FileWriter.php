<?php declare(strict_types=1); namespace BapCat\Persist;

use BapCat\Propifier\PropifierTrait;

/**
 * Defines a class capable of writing to a file
 *
 * @property-read  File  $file  The file to write to
 */
abstract class FileWriter {
  use PropifierTrait;

  /** @var  File  $file */
  private $file;

  /**
   * @param  File  $file  The file to write to
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
   * Writes an arbitrary block of data to the file
   *
   * @param  mixed  $data  The data to write to the file
   *
   * @return  int  The number of bytes written
   */
  public abstract function write($data): int;
}
