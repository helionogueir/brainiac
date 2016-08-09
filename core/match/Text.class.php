<?php

namespace helionogueir\brainiac\match;

use stdClass;
use DirectoryIterator;
use helionogueir\brainiac\match\Input;
use helionogueir\typeBoxing\type\String;
use helionogueir\brainiac\match\Pattern;

/**
 * Find match in text:
 * - Load de pattern
 * - Compare text with pattern
 *
 * @author Helio Nogueira <helio.nogueir@gmail.com>
 * @version v1.0.0
 */
class Text {

  /**
   * Match value:
   * - Find value in pattern
   * 
   * @param helionogueir\brainiac\match\Input $input Input data
   * @return null
   */
  public function match(Input $input, Array &$data) {
    if (!$input->getPatternName()->isEmpty()) {
      $this->findValueByPatten($input, $input->getPatternName());
    } else {
      foreach (new DirectoryIterator($input->getDirectory()) as $fileInfo) {
        if (!$fileInfo->isDot() && $fileInfo->isFile()) {
          $this->findValueByPatten($input, $data, new String($fileInfo->getPathname()));
        }
      }
    }
    return null;
  }

  /**
   * Find pattern:
   * - Check text with pattern(s)
   * 
   * @param helionogueir\brainiac\match\Input $input Input data
   * @return null
   */
  private function findValueByPatten(Input $input, Array &$data, String $filename) {
    $pattern = new Pattern($filename);
    foreach ($pattern->getPattern() as $regex) {
      if (preg_match($regex, $input->getText())) {
        $object = new stdClass();
        $object->name = "{$pattern->getName()}";
        $object->value = "{$input->getValue()}";
        $object->valueFilter = "{$pattern->applyFilter($input->getValue())}";
        $object->text = "{$input->getText()}";
        $object->pattern = "{$filename}";
        $data[$pattern->getName()] = $object;
        break;
      }
    }
    return null;
  }

}
