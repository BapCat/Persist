<?php namespace BapCat\Persist\Drivers\Local;

use BapCat\Persist\FileWriter;

/**
 * A reader capable of reading LocalFiles
 *
 * @author    Corey Frenette
 * @copyright Copyright (c) 2015, BapCat
 */
class LocalFileWriter extends FileWriter {
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
  public function write($data) {
    $written = fwrite($this->ptr, $data);
    
    if($written === false) {
      //@TODO
      throw new \Exception("Error writing to file [{$this->file->full_path}]");
    }
    
    return $written;
  }
}
