<?php

namespace openacalendar\staticweb\filters;

use openacalendar\staticweb\Site;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class EventFilter {


  /** @var Site **/
  protected $site;

  /** @var TimeSource **/
  protected $timesource;

  public function __construct(Site $site, $app) {
    $this->site = $site;
    $this->timesource = $app['timesource'];
  }

  protected $built = false;

  protected $events;

  protected $presentOrFutureOnly = false;

  public function setPresentOrFutureOnly($value) {
    $this->presentOrFutureOnly = $value;
  }

  public function build() {
    $this->events = array();
    foreach($this->site->getEvents() as $event) {

        $include = true;

        if ($this->presentOrFutureOnly) {
          if ($event->getEnd()->getTimeStamp() < $this->timesource->time() ) {
            $include = false;
          }
        }

        if ($include) {
          $this->events[] = $event;
        }

    }
    $this->built = true;
  }

  public function get() {
    if (!$this->built) {
      $this->build();
    }
    return $this->events;
  }

}
