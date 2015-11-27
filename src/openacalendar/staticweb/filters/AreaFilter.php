<?php

namespace openacalendar\staticweb\filters;

use openacalendar\staticweb\models\Country;
use openacalendar\staticweb\Site;
use openacalendar\staticweb\models\Group;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class AreaFilter {


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

  // Filters!!

  protected $presentOrFutureOnly = false;

  public function setPresentOrFutureOnly($value) {
    $this->presentOrFutureOnly = $value;
  }

  /** @var Group **/
  protected $group;

  public function setGroup(Group $group) {
      $this->group = $group;
  }

    /** @var Country */
    protected $country;

    /**
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

  // Processing .....

  public function build() {
    $this->events = array();
    foreach($this->site->getEvents() as $event) {

        $include = true;

        if ($this->presentOrFutureOnly) {
          if ($event->getEnd()->getTimeStamp() < $this->timesource->time() ) {
            $include = false;
          }
        }

        if ($this->group) {
          $foundIt = false;
          foreach($event->getGroupSlugs() as $slug) {
            if ($slug == $this->group->getSlug()) {
              $foundIt= true;
            }
          }
          if (!$foundIt) {
            $include = false;
          }
        }
        if ($this->country) {
            if ($event->getCountry()->getCode() != $this->country->getCode()) {
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
