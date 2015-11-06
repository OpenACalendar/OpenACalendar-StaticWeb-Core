<?php

namespace openacalendar\staticweb;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class OutFolder
{

  protected $folder;

  public function __construct($folder) {
    $this->folder = $folder;
    if (!file_exists($this->folder)) {
      mkdir($this->folder);
    }
  }

  public function addFileContents($folderName, $fileName, $contents) {
    if ($folderName) {
      if (!file_exists($this->folder . DIRECTORY_SEPARATOR .  $folderName )) {
        mkdir($this->folder . DIRECTORY_SEPARATOR .  $folderName);
      }
    }
    file_put_contents(
      $this->folder . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $fileName,
      $contents
    );
  }



}
