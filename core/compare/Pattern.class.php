<?php

namespace helionogueir\changedirective\cgi;

use Exception;
use helionogueir\typeBoxing\type\Boolean;

/**
 * Configuration of session:
 * - Load language pettern in application;
 *
 * @author Helio Nogueira <helio.nogueir@gmail.com>
 * @version v1.0.0
 */
class Session {

  /**
   * Block construct the class, because this class is static
   * @return false
   */
  public function __construct() {
    return false;
  }

  /**
   * Make language:
   * - Mount language configuration;
   * 
   * @return helionogueir\typeBoxing\type\Boolean Return if language was implemented
   */
  public static final function start() {
    $auth = false;
    try {
      if (!isset($_SESSION)) {
        session_start();
      }
      $auth = true;
    } catch (Exception $ex) {
      $auth = false;
      throw $ex;
    }
    return new Boolean($auth);
  }

}
