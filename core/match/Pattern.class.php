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
  private $behavior = null;

  /**
   * Contruct pattern:
   * - Get the filename and construct the pattern
   * 
   * @param helionogueir\typeBoxing\type\String $filename Keywords of find the pattern
   * @return null
   */
  public function __construct(String $filename) {
    if (is_file($filename) && is_readable($filename) && preg_match('/^(.*)\/(.*)\.(json)$/', $filename)) {
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
        $this->rule->case = (isset($this->rule->case)) ? $decode->rule->case : null;
        $this->rule->trim = (isset($this->rule->trim)) ? $decode->rule->trim : false;
        $this->rule->test = (isset($this->rule->test)) ? $decode->rule->test : null;
        $this->rule->numeric = (isset($this->rule->numeric)) ? $decode->rule->numeric : false;
        $this->rule->length = (isset($this->rule->length)) ? $decode->rule->length : null;
        if (!isset($this->rule->lengthBetween, $this->rule->lengthBetween->min, $this->rule->lengthBetween->max)) {
          $this->rule->lengthBetween = null;
        }
      }
      if ($decode->behavior instanceof stdClass) {
        $this->behavior = $decode->behavior;
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

  public function getName() {
    return $this->name;
  }

  public function getPattern() {
    return $this->pattern;
  }

  public function getRule() {
    return $this->rule;
  }

  public function mergeBehavior(Array $behavior = null) {
    if (count($behavior)) {
      return (object) array_merge((array) $this->behavior, $behavior);
    } else {
      return $this->behavior;
    }
  }

  /**
   * Apply filter
   * - Format value conform rules
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will test
   * @return helionogueir\typeBoxing\type\String Clean value
   */
  public function applyFilter(String $value) {
    if (!$value->isEmpty()) {
      $this->ruleClear($value);
      $this->ruleCase($value);
      $this->ruleTrim($value);
      $this->ruleTest($value);
      $this->ruleNumeric($value);
      $this->ruleLength($value);
      $this->ruleLengthBetween($value);
    }
    return $value;
  }

  /**
   * Rule clear
   * - Clean the value
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will transform
   * @return null
   */
  private function ruleClear(String &$value) {
    if (!empty($this->rule->clear)) {
      $value = new String(@preg_replace($this->rule->clear, null, $value));
    }
    return null;
  }

  /**
   * Rule case
   * - Transform in upper case
   * - Transform in lower case
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will transform
   * @return null
   */
  private function ruleCase(String &$value) {
    switch (strtolower($this->rule->case)) {
      case 'upper':
        $value = new String(strtoupper($value));
        break;
      case 'lower':
        $value = new String(strtolower($value));
        break;
    }
    return null;
  }

  /**
   * Rule trim
   * - Trim the value
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will transform
   * @return null
   */
  private function ruleTrim(String &$value) {
    if ((bool) $this->rule->trim) {
      $value = new String(trim($value));
    }
    return null;
  }

  /**
   * Rule test
   * - Test the value
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will transform
   * @return null
   */
  private function ruleTest(String &$value) {
    if (!empty($this->rule->test) && !@preg_match($this->rule->test, $value)) {
      $value = new String('');
    }
    return null;
  }

  /**
   * Rule numeric
   * - Numeric the value
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will transform
   * @return null
   */
  private function ruleNumeric(String &$value) {
    if (isset($this->rule->numeric, $this->rule->numeric->decimal, $this->rule->numeric->separator)) {
      if ((bool) $this->rule->numeric->decimal && (strlen($value) >= $this->rule->numeric->decimal)) {
        $pattern = "/(\d{1,})(\d{{$this->rule->numeric->decimal}})/";
        $number = preg_replace($pattern, "$1{$this->rule->numeric->separator}", $value);
        $number .= preg_replace($pattern, '$2', $value);
        $value = new String($number);
      }
    }
    return null;
  }

  /**
   * Rule length
   * - Length the value
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will transform
   * @return null
   */
  private function ruleLength(String &$value) {
    if (!is_null($this->rule->length) && (strlen($value) != $this->rule->length)) {
      $value = new String('');
    }
    return null;
  }

  /**
   * Rule between length
   * - Between length the value
   * 
   * @param helionogueir\typeBoxing\type\String $value Value to be will transform
   * @return null
   */
  private function ruleLengthBetween(String &$value) {
    if (!empty($this->rule->lengthBetween)) {
      $min = (strlen($value) >= $this->rule->lengthBetween->min);
      $max = (strlen($value) <= $this->rule->lengthBetween->max);
      if (!($min && $max)) {
        $value = new String('');
      }
    }
    return null;
  }

}
