<?php declare(strict_types=1); namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\Directory;

use BapCat\Persist\PathAlreadyExistsException;
use DirectoryIterator;
use RuntimeException;

/**
 * A directory that exists on a locally-accessible filesystem
 *
 * @property-read  LocalDriver  $driver     The filesystem driver that backs this Path
 * @property-read  string       $full_path  The full filesystem path to this directory
 */
class LocalDirectory extends Directory {
  /**
   * @param  LocalDriver  $driver  The filesystem driver this file or directory belongs to
   * @param  string       $path    The path of this file or directory
   */
  public function __construct(LocalDriver $driver, string $path) {
    parent::__construct($driver, $path);
  }

  /**
   * {@inheritDoc}
   */
  public function create(int $permissions = 0755): void {
    $concurrentDirectory = $this->driver->getFullPath($this->path);
    if(!mkdir($concurrentDirectory, $permissions) && !is_dir($concurrentDirectory)) {
      throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }
  }

  /**
   * {@inheritDoc}
   */
  protected function loadChildren(): array {
    $paths = [];

    foreach(new DirectoryIterator($this->full_path) as $path) {
      if($path->isDot()) {
        continue;
      }

      $full_path = "{$this->path}/$path";

      if($this->driver->isFile($full_path)) {
        $paths[] = $this->driver->getFile($full_path);
      } else {
        $paths[] = $this->driver->getDirectory($full_path);
      }
    }

    return $paths;
  }

  /**
   * {@inheritDoc}
   */
  public function move(Directory $dest): bool {
    if(!($dest instanceof self)) {
      throw new RuntimeException('Files can only be moved within their own filesystem');
    }

    return rename($this->full_path, $dest->full_path);
  }

  /**
   * {@inheritDoc}
   */
  public function copy(Directory $dest): bool {
    if(!$dest->exists) {
      try {
        $dest->create();
      } catch(PathAlreadyExistsException $e) { }
    }

    foreach($this->children as $child) {
      if($child instanceof Directory) {
        $path = $this->driver->getDirectory($dest->path . '/' . $child->name);
      } else {
        $path = $this->driver->getFile($dest->path . '/' . $child->name);
      }

      if(!$child->copy($path)) {
        return false;
      }
    }

    return true;
  }

  /**
   * {@inheritDoc}
   */
  public function delete(): bool {
    foreach($this->children as $child) {
      if(!$child->delete()) {
        return false;
      }
    }

    return rmdir($this->full_path);
  }

  /* NON-STANDARD METHODS */
  /**
   * @return  string
   */
  protected function getFullPath(): string {
    return $this->driver->getFullPath($this->path);
  }
}
