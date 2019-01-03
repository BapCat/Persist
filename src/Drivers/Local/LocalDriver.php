<?php declare(strict_types=1); namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\Directory;
use BapCat\Persist\Driver;
use BapCat\Persist\File;
use BapCat\Persist\Path;
use BapCat\Persist\PathNotFoundException;
use DateTime;

/**
 * A driver for interacting with local files
 *
 * @property-read  string    $root
 * @property-read  string[]  $full_path
 */
class LocalDriver extends Driver {
  /** @var  string  $root  The root directory */
  private $root;

  /**
   * @param  string  $root  The root directory (all paths will be relative to this)
   */
  public function __construct(string $root) {
    $this->root = $root;
  }

  /**
   * {@inheritDoc}
   * @return  LocalFile
   */
  protected function instantiateFile(string $path): File {
    return new LocalFile($this, $path);
  }

  /**
   * {@inheritDoc}
   * @return  LocalDirectory
   */
  protected function instantiateDir(string $path): Directory {
    return new LocalDirectory($this, $path);
  }

  /**
   * {@inheritDoc}
   * @return  LocalFile
   */
  public function createFile(string $path): File {
    $localFile = $this->instantiateFile($path);
    $localFile->create();
    return $localFile;
  }

  /**
   * {@inheritDoc}
   * @return  LocalDirectory
   */
  public function createDirectory(string $path): Directory {
    $localDirectory = $this->instantiateDir($path);
    $localDirectory->create();
    return $localDirectory;
  }

  /**
   * {@inheritDoc}
   */
  public function isDir(string $path): bool {
    return is_dir($this->getFullPath($path));
  }

  /**
   * {@inheritDoc}
   */
  public function isFile(string $path): bool {
    return is_file($this->getFullPath($path));
  }

  /**
   * {@inheritDoc}
   */
  public function exists(Path $path): bool {
    return file_exists($this->getFullPath($path->path));
  }

  /**
   * {@inheritDoc}
   */
  public function isLink(Path $path): bool {
    return is_link($this->getFullPath($path->path));
  }

  /**
   * {@inheritDoc}
   */
  public function isReadable(Path $path): bool {
    return is_readable($this->getFullPath($path->path));
  }

  /**
   * {@inheritDoc}
   */
  public function isWritable(Path $path): bool {
    return is_writable($this->getFullPath($path->path));
  }

  /**
   * {@inheritDoc}
   */
  public function size(File $file): int {
    $size = @filesize($this->getFullPath($file->path));

    if($size === false) {
      throw new PathNotFoundException($file->path);
    }

    return $size;
  }

  /**
   * {@inheritDoc}
   */
  public function modified(Path $path): int {
    $time = @filemtime($this->getFullPath($path->path));

    if($time === false) {
      throw new PathNotFoundException($path->path);
    }

    return $time;
  }

  /* NON-STANDARD METHODS */
  /**
   * Get the root
   *
   * @return  string
   */
  public function getRoot(): string {
    return $this->root;
  }

  /**
   * Get the full path of a file, prefixed with the root
   *
   * @param  string  $path  The path (with no leading slash)
   *
   * @return  string
   */
  public function getFullPath($path): string {
    return "{$this->root}/$path";
  }
}
