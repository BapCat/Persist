<?php namespace BapCat\Persist;

class File extends Path {
  /**
   * @return  void
   */
  public function create() {
    if($this->exists) {
      throw new PathAlreadyExistsException($this->path);
    }
    
    fclose(fopen($this->path, 'w', false, $this->context));
    
    return $this;
  }
  
  /**
   * @return  int  The size of the file
   */
  protected function getSize() {
    throw new Exception();
  }
}
