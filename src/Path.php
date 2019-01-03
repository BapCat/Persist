<?php declare(strict_types=1); namespace BapCat\Persist;

use BapCat\Propifier\PropifierTrait;
use DateTime;

use function dirname;

/**
 * Defines a basic element of a filesystem, which may be a file or directory
 *
 * @property-read  Driver     $driver       The filesystem driver that backs this Path
 * @property-read  string     $path         The path of this file or directory
 * @property-read  string     $name         The name of this file or directory
 * @property-read  Directory  $parent       The parent directory of this file or directory
 * @property-read  bool       $exists       If this file or directory exists
 * @property-read  bool       $is_link      If this file or directory is a symlink
 * @property-read  bool       $is_readable  If this file or directory is readable
 * @property-read  bool       $is_writable  If this file or directory is writable
 * @property-read  DateTime   $modified     The last time this file or directory was modified
 */
abstract class Path {
  use PropifierTrait;

  /** @var  Driver  $driver  The filesystem driver */
  private $driver;

  /** @var  string  $path  The path of this file or directory */
  private $path;

  /** @var  string  $name  The name of this file or directory */
  private $name;

  /**
   * @param  Driver  $driver  The filesystem driver this file or directory belongs to
   * @param  string  $path    The path of this file or directory
   */
  public function __construct(Driver $driver, string $path) {
    $this->driver = $driver;
    $this->path   = $path;

    $this->name = basename($path);
  }

  /**
   * Gets a string representation of this file or directory
   *
   * @return  string
   */
  public function __toString(): string {
    return __CLASS__ . "[{$this->path}]";
  }

  /**
   * @return  Driver
   */
  protected function getDriver(): Driver {
    return $this->driver;
  }

  /**
   * @return  string
   */
  protected function getPath(): string {
    return $this->path;
  }

  /**
   * @return  string
   */
  protected function getName(): string {
    return $this->name;
  }

  /**
   * @return  Directory
   *
   * @throws  NotADirectoryException
   */
  protected function getParent(): Directory {
    return $this->driver->getDirectory(dirname($this->path));
  }

  /**
   * @return  bool
   */
  protected function getExists(): bool {
    return $this->driver->exists($this);
  }

  /**
   * @return  bool
   */
  protected function getIsLink(): bool {
    return $this->driver->isLink($this);
  }

  /**
   * @return  bool
   */
  protected function getIsReadable(): bool {
    return $this->driver->isReadable($this);
  }

  /**
   * @return  bool
   */
  protected function getIsWritable(): bool {
    return $this->driver->isWritable($this);
  }

  /**
   * @return  int
   *
   * @throws  PathNotFoundException
   */
  protected function getModified(): int {
    return $this->driver->modified($this);
  }

  /**
   * Creates this file or directory
   *
   * @param  int  $permissions  Unix octal permissions (default: 0755)
   *
   * @return  void
   *
   * @throws  PathAlreadyExistsException
   */
  public abstract function create(int $permissions = 0755): void;

  /**
   * Deletes this file or directory
   *
   * @return  bool  True on success, false otherwise
   */
  public abstract function delete(): bool;
}
