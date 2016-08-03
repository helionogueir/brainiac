<?php

namespace helionogueir\brainiac\match;

use stdClass;
use Exception;
use helionogueir\languagepack\Lang;
use helionogueir\typeBoxing\type\String;
use helionogueir\typeBoxing\type\json\Decode;

/**
 * Boxing pattern:
 * - Boxing pattern
 *
 * @author Helio Nogueira <helio.nogueir@gmail.com>
 * @version v1.0.0
 */
class Pattern {

  private $name = null;
  private $pattern = Array();
  private $rule = null;

  /**
   * Contruct pattern:
   * - Get the filename and construct the pattern
   * 
   * @param helionogueir\typeBoxing\type\String $filename Keywords of find the pattern
   * @return null
   */
  public function __construct(String $filename) {
    if (is_file($filename) && is_readable($filename)) {
      $decode = new Decode(file_get_contents($filename));
      $this->name = $decode->name;
      if (empty($this->name)) {
        Lang::addRoot(new String(\helionogueir\brainiac\autoload\LanguagePack::PACKAGE), new String(\helionogueir\brainiac\autoload\LanguagePack::PATH));
        throw new Exception(Lang::get(new String('brainiac:paramter:isnotnull'), new String('helionogueir/brainiac'), Array('value' => 'name')));
      }
      $this->pattern = $decode->pattern;
      if (!is_array($this->pattern) || !count($this->pattern)) {
        Lang::addRoot(new String(\helionogueir\brainiac\autoload\LanguagePack::PACKAGE), new String(\helionogueir\brainiac\autoload\LanguagePack::PATH));
        throw new Exception(Lang::get(new String('brainiac:paramter:isnotnull'), new String('helionogueir/brainiac'), Array('value' => 'pattern')));
      }
      $this->rule = $decode->rule;
      if ($this->rule instanceof stdClass) {
        $this->rule->clear = (isset($this->rule->clear)) ? $decode->rule->clear : null;
      }
    } else {
      Lang::addRoot(new String(\helionogueir\brainiac\autoload\LanguagePack::PACKAGE), new String(\helionogueir\brainiac\autoload\LanguagePack::PATH));
      throw new Exception(Lang::get(new String('brainiac:match:filename:invalid'), new String('helionogueir/brainiac')));
    }
    return null;
  }

  /**
   * Default value
   * - Case null return this value
   * 
   * @return helionogueir\typeBoxing\type\String Value
   */
  public function defaultValue() {
    return new String($this->rule->defaultValue);
  }

  /**
   * Clean value
   * - Clean the value
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will test
   * @return helionogueir\typeBoxing\type\String Clean value
   */
  public function clearValue(String $value) {
    if (!$value->isEmpty() && !empty($this->rule->clear)) {
      $value = new String(@preg_replace($this->rule->clear, null, $value));
    }
    return $value;
  }

  public function getName() {
    return $this->name;
  }

  public function getPattern() {
    return $this->pattern;
  }

  public function getRule() {
    return $this->rule;
  }

}
