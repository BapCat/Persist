<?php namespace BapCat\Persist\Drivers\Local;

use Exception;

class PathOutsideOfRootException extends Exception {
  public function __construct($path) {
    parent::__construct("[$path] is outside of the root directory");
  }
}
