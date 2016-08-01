<?php

namespace helionogueir\changedirective\cgi;

use helionogueir\typeBoxing\type\String;
use helionogueir\typeBoxing\type\Boolean;

/**
 * Configuration of debug:
 * - Load debug pettern in application;
 *
 * @author Helio Nogueira <helio.nogueir@gmail.com>
 * @version v1.0.0
 */
class Debug {

  const DEVELOPER = 'developer';
  const HOMOLOGATION = 'homologation';
  const PRODUCTION = 'production';

  /**
   * Block construct the class, because this class is static
   * @return false
   */
  public function __construct() {
    return false;
  }

  /**
   * Make debug:
   * - Mount debug configuration
   * 
   * @param helionogueir\typeBoxing\type\String $mode Mode name
   * @return helionogueir\typeBoxing\type\Boolean Return if debug was implemented
   */
  public static final function set(String $mode = null) {
    switch ($mode) {
      case Debug::HOMOLOGATION:
      case Debug::DEVELOPER:
        ini_set('display_errors', true);
        error_reporting(E_ALL);
        $auth = true;
        break;
      case Debug::PRODUCTION:
      default:
        ini_set('display_errors', false);
        error_reporting(E_ERROR);
        $auth = true;
        break;
    }
    return new Boolean(true);
  }

}
