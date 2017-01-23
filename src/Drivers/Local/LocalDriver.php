<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\Directory;
use BapCat\Persist\Driver;
use BapCat\Persist\File;
use BapCat\Persist\NotADirectoryException;

class LocalDriver extends Driver {
  private $root;
  
  public function __construct($root) {
    if(!is_dir($root)) {
      throw new NotADirectoryException($root);
    }
    
    $this->root = $root;
    
    parent::__construct(LocalStreamWrapper::STREAM_KEY, stream_context_create([
      LocalStreamWrapper::STREAM_KEY => [
        'root' => $root,
      ],
    ]));
  }
}
