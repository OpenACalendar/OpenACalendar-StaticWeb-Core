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
class TemporaryFolder
{

  protected $folder;

  public function get() {
    if (!$this->folder) {
      $this->folder = sys_get_temp_dir() . '/openacalendarstaticweb'.rand();
      while(file_exists($this->folder)) {
        $this->folder = sys_get_temp_dir() . '/openacalendarstaticweb'.rand();
      }
      mkdir($this->folder);
    }
    return $this->folder;
  }

}
