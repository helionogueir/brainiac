<?php

namespace helionogueir\brainiac\match;

use Exception;
use helionogueir\languagepack\Lang;
use helionogueir\typeBoxing\type\String;

/**
 * Input pattern:
 * - Pattern of input of match
 *
 * @author Helio Nogueira <helio.nogueir@gmail.com>
 * @version v1.0.0
 */
class Input {

  private $text = null;
  private $value = null;
  private $directory = null;
  private $patternName = null;

  /**
   * Contruct basic parameters:
   * - Get basic paramiters of match
   * 
   * @param helionogueir\typeBoxing\type\String $text Keywords of find the pattern
   * @param helionogueir\typeBoxing\type\String $value Value return case the pattern is match
   * @param helionogueir\typeBoxing\type\String $directory Path directory of pattern
   * @param helionogueir\typeBoxing\type\String $patternName Name of pattern
   * @return null
   */
  public function __construct(String $text, String $value, String $directory, String $patternName = null) {
    $this->text = $text;
    if ($this->text->isEmpty()) {
      Lang::addRoot(new String(\helionogueir\brainiac\autoload\LanguagePack::PACKAGE), new String(\helionogueir\brainiac\autoload\LanguagePack::PATH));
      throw new Exception(Lang::get(new String('brainiac:paramter:isnotnull'), new String('helionogueir/brainiac'), Array('value' => 'text')));
    }
    $this->value = $value;
    $this->directory = $directory;
    if ($this->directory->isEmpty() || !is_dir($this->directory) || !is_readable($this->directory) || (count(scandir($this->directory)) <= 2)) {
      Lang::addRoot(new String(\helionogueir\brainiac\autoload\LanguagePack::PACKAGE), new String(\helionogueir\brainiac\autoload\LanguagePack::PATH));
      throw new Exception(Lang::get(new String('brainiac:paramter:isnotnull'), new String('helionogueir/brainiac'), Array('value' => 'directory')));
    }
    $this->patternName = $patternName;
    if (!is_null($this->patternName) && !$this->patternName->isEmpty()) {
      if (!is_file($this->patternName) || !is_readable($this->patternName)) {
        Lang::addRoot(new String(\helionogueir\brainiac\autoload\LanguagePack::PACKAGE), new String(\helionogueir\brainiac\autoload\LanguagePack::PATH));
        throw new Exception(Lang::get(new String('brainiac:paramter:isnotnull'), new String('helionogueir/brainiac'), Array('value' => 'patternName')));
      }
    }
    return null;
  }

  public function getText() {
    return $this->text;
  }

  public function getValue() {
    return $this->value;
  }

  public function getDirectory() {
    return $this->directory;
  }

  public function getPatternName() {
    if (is_null($this->patternName) || $this->patternName->isEmpty()) {
      return new String('');
    } else {
      return new String($this->directory . DIRECTORY_SEPARATOR . $this->patternName);
    }
  }

}
