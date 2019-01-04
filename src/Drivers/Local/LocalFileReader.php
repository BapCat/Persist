<?php declare(strict_types=1); namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\FileReader;
use BapCat\Persist\FileReadException;

/**
 * A reader capable of reading LocalFiles
 *
 * @property-read  LocalFile  $file  The file to read
 */
class LocalFileReader extends FileReader {
  /** @var  resource  $ptr */
  private $ptr;

  /**
   * {@inheritdoc}
   *
   * @param  LocalFile  $file  The file to read from
   * @param  resource   $ptr   A file pointer to the file
   */
  public function __construct(LocalFile $file, $ptr) {
    parent::__construct($file);
    $this->ptr = $ptr;
  }

  /**
   * {@inheritdoc}
   */
  protected function getLength(): int {
    $size = @filesize($this->file->full_path);

    if($size === false) {
      throw new FileReadException($this->file, 'Error getting file length');
    }

    return $size;
  }

  /**
   * {@inheritdoc}
   */
  protected function getHasMore(): bool {
    return $this->remaining !== 0;
  }

  /**
   * {@inheritdoc}
   */
  protected function getRemaining(): int {
    return $this->length - @ftell($this->ptr);
  }

  /**
   * {@inheritdoc}
   */
  public function read(int $length = 0): string {
    if($length === 0) {
      $length = $this->length;
    }

    $read = fread($this->ptr, $length);

    if($read === false) {
      throw new FileReadException($this->file, 'Error reading from file');
    }

    return $read;
  }
}
