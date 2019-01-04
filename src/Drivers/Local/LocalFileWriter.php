<?php declare(strict_types=1); namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\FileWriteException;
use BapCat\Persist\FileWriter;

/**
 * A reader capable of reading LocalFiles
 *
 * @property-read  LocalFile  $file  The file to read
 */
class LocalFileWriter extends FileWriter {
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
  public function write($data): int {
    $written = fwrite($this->ptr, $data);

    if($written === false) {
      throw new FileWriteException($this->file, 'Error writing to file');
    }

    return $written;
  }
}
