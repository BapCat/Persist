<?php

require_once __DIR__ . '/_mocks.php';
require_once __DIR__ . '/FileCreatorTrait.php';

use BapCat\Persist\Drivers\Local\LocalFile;
use BapCat\Persist\PathAlreadyExistsException;

class LocalFileTest extends PHPUnit_Framework_TestCase {
  use FileCreatorTrait;
  
  private $driver;
  
  public function setUp() {
    $this->createTestFiles();
    $this->driver = mockLocalDriver($this, $this->datadir);
  }
  
  public function tearDown() {
    $this->deleteTestFiles();
  }
  
  public function testConstruct() {
    $localFile = new LocalFile($this->driver, $this->filename);
    $this->assertEquals($localFile, $localFile->makeLocal());
    $this->assertEquals($localFile->full_path, $this->driver->getFullPath($this->filename));
  }
  
  public function testAlreadyExists() {
    // NOTE: Comes from FileCreatorTrait
    $localFile = new LocalFile($this->driver, $this->filename);
    $this->setExpectedException(PathAlreadyExistsException::class);
    $localFile->create();
  }
  
  public function testCreate() {
    $localFile = new LocalFile($this->driver, "{$this->filename}-new-create");
    $localFile->create();
  }
  
}
