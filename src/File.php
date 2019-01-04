<?php declare(strict_types=1); namespace BapCat\Persist;

/**
 * Defines a file in a persistent filesystem
 *
 * @property-read  int  $size  The size of this file
 */
abstract class File extends Path {
  /**
   * @return  int
   *
   * @throws  PathNotFoundException
   */
  protected function getSize(): int {
    return $this->driver->size($this);
  }

  /**
   * Caches a copy of this file on the local filesystem (if it isn't already)
   *
   * @return  File  A File instance that points to the local file
   */
  public abstract function makeLocal(): File;

  /**
   * Opens a file for reading
   *
   * @param  callable  $read  Called once the file is opened
   *
   * @return  void
   *
   * @throws  PathNotFoundException
   */
  public abstract function read(callable $read): void;

  /**
   * Opens a file and returns its contents
   *
   * @return  string  The contents of the file
   *
   * @throws  FileReadException
   */
  public abstract function readAll(): string;

  /**
   * Opens a file for writing
   *
   * @param  callable  $write  Called once the file is opened
   *
   * @return  void
   *
   * @throws  PathNotFoundException
   */
  public abstract function write(callable $write): void;

  /**
   * Opens a file and writes to it
   *
   * @param  string  $contents  The contents to write
   *
   * @return  int  The length of the data written
   *
   * @throws  FileWriteException
   */
  public abstract function writeAll(string $contents): int;

  /**
   * Moves a file to a new location
   *
   * @param  File  $dest  Where to move the file
   *
   * @return  bool  True on success, false otherwise
   */
  public abstract function move(File $dest): bool;

  /**
   * Copies a file to a new location
   *
   * @param  File  $dest  Where to copy the file
   *
   * @return  bool  True on success, false otherwise
   */
  public abstract function copy(File $dest): bool;
}
