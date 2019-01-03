<?php declare(strict_types=1); namespace BapCat\Persist;

/**
 * Defines a directory in a persistent filesystem
 *
 * @property-read  Path[]  $children
 * @property-read  Path[]  $child
 */
abstract class Directory extends Path {
  /**
   * Loads the children of this directory
   *
   * @return  Path[]  An array containing the children of this directory
   *
   * @throws  NotADirectoryException
   * @throws  NotAFileException

   */
  protected abstract function loadChildren(): array;

  /**
   * Gets the children of this directory
   *
   * @return  Path[]  An array containing the children of this directory
   *
   * @throws  NotADirectoryException
   * @throws  NotAFileException
   */
  protected function getChildren(): array {
    return $this->loadChildren();
  }

  /**
   * Gets a child of this directory
   *
   * @param  string  $name  The name of the child to get
   *
   * @return  Path  The child of this directory
   *
   * @throws NotADirectoryException
   * @throws NotAFileException
   */
  protected function getChild(string $name): Path {
    $full_path = "{$this->path}/$name";

    if($this->driver->isDir($full_path)) {
      return $this->driver->getDirectory($full_path);
    }

    return $this->driver->getFile($full_path);
  }

  /**
   * Moves a directory to a new location
   *
   * @param  Directory  $dest  Where to move the directory
   *
   * @return  bool  True on success, false otherwise
   */
  public abstract function move(Directory $dest): bool;

  /**
   * Copies a directory to a new location
   *
   * @param  Directory  $dest  Where to copy the file
   *
   * @return  bool  True on success, false otherwise
   *
   * @throws  NotADirectoryException
   * @throws  NotAFileException

   */
  public abstract function copy(Directory $dest): bool;
}
