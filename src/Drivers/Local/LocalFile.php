<?php declare(strict_types=1); namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\File;
use BapCat\Persist\PathAlreadyExistsException;
use RuntimeException;

/**
 * A file residing on a local filesystem
 *
 * @property-read  LocalDriver  $driver     The filesystem driver that backs this Path
 * @property-read  string       $full_path  The full filesystem path to this directory
 */
class LocalFile extends File {
  /**
   * {@inheritdoc}
   */
  public function __construct(LocalDriver $driver, $path) {
    parent::__construct($driver, $path);
  }

  /**
   * {@inheritdoc}
   */
  public function create(int $permissions = 0755): void {
    if($this->driver->exists($this)) {
      throw new PathAlreadyExistsException($this->path);
    }

    $fullPath = $this->driver->getFullPath($this->path);

    $handle = fopen($fullPath, 'wb');
    chmod($fullPath, $permissions);
    fclose($handle);
  }

  /**
   * {@inheritdoc}
   *
   * This is a no-op on the Local driver
   *
   * @return  LocalFile
   */
  public function makeLocal(): File {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function read(callable $read): void {
    $ptr = fopen($this->full_path, 'rb');
    $read(new LocalFileReader($this, $ptr));
    fclose($ptr);
  }

  /**
   * {@inheritDoc}
   */
  public function readAll(): string {
    $contents = @file_get_contents($this->full_path);

    if($contents === false) {
      //@TODO
      throw new \Exception("Error reading file contents [{$this->full_path}]");
    }

    return $contents;
  }

  /**
   * {@inheritdoc}
   */
  public function write(callable $write): void {
    $ptr = fopen($this->full_path, 'ab');
    $write(new LocalFileWriter($this, $ptr));
    fclose($ptr);
  }

  /**
   * {@inheritDoc}
   */
  public function writeAll(string $contents): int {
    $parent = $this->parent;

    if(!$parent->exists) {
      //@TODO
      throw new \Exception("Parent doesn't exist");
    }

    $temp = tempnam($parent->full_path, '');

    if($temp === false) {
      //@TODO
      throw new \Exception("Error creating temp file");
    }

    chmod($temp, 0666 & ~umask());

    $written = @file_put_contents($temp, $contents);

    if($written === false) {
      //@TODO
      throw new \Exception("Error writing file contents [{$this->full_path}]");
    }

    $renamed = rename($temp, $this->full_path);

    if($renamed === false) {
        //@TODO
        throw new \Exception("Error while renaming [$temp] to [{$this->full_path}]");
    }

    return $written;
  }

  /**
   * {@inheritDoc}
   */
  public function move(File $dest): bool {
    if(!($dest instanceof self)) {
      throw new RuntimeException('Files can only be moved within their own filesystem');
    }

    return rename($this->full_path, $dest->full_path);
  }

  /**
   * {@inheritDoc}
   */
  public function copy(File $dest): bool {
    if(!($dest instanceof self)) {
      throw new RuntimeException('Files can only be moved within their own filesystem');
    }

    return copy($this->full_path, $dest->full_path);
  }

  /**
   * {@inheritDoc}
   */
  public function delete(): bool {
    return unlink($this->full_path);
  }

  /* NON-STANDARD METHODS */

  /**
   * Returns the full path of this file on the local filesystem
   *
   * @return  string  The full path of this file
   */
  protected function getFullPath(): string {
    return $this->driver->getFullPath($this->path);
  }
}
