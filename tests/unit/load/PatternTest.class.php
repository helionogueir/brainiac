<?php

namespace helionogueir\changedirective\tests\unit\cgi;

use PHPUnit_Framework_TestCase;
use helionogueir\typeBoxing\type\String;
use helionogueir\changedirective\cgi\Debug;

/**
 * Configuration of debug:
 * - Load debug pettern in application;
 *
 * @author Helio Nogueira <helio.nogueir@gmail.com>
 * @version v1.0.0
 */
class DebugTest extends PHPUnit_Framework_TestCase {

  public function testSetDeveloper() {
    Debug::set(new String(Debug::DEVELOPER));
    $this->assertEquals(ini_get('display_errors'), '1');
    $this->assertEquals(ini_get('error_reporting'), E_ALL);
  }

  public function testSetHomologation() {
    Debug::set(new String(Debug::HOMOLOGATION));
    $this->assertEquals(ini_get('display_errors'), '1');
    $this->assertEquals(ini_get('error_reporting'), E_ALL);
  }

  public function testSetProduction() {
    Debug::set(new String(Debug::PRODUCTION));
    $this->assertEquals(ini_get('display_errors'), false);
    $this->assertEquals(ini_get('error_reporting'), E_ERROR);
  }

}
