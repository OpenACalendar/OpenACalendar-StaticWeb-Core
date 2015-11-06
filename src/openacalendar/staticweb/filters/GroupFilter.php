<?php

namespace openacalendar\staticweb\filters;

use openacalendar\staticweb\Site;
use openacalendar\staticweb\models\Event;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class GroupFilter {


  /** @var Site **/
  protected $site;

  public function __construct(Site $site, $app) {
    $this->site = $site;
  }

  protected $built = false;

  protected $groups;

  // Filters!!

  /** @var Event **/
  protected $event;

  public function setEvent(Event $event) {
      $this->event = $event;
  }

  // Processing .....

  public function build() {
    $this->groups = array();
    foreach($this->site->getGroups() as $group) {

        $include = true;

        if ($this->event) {
          $foundIt = false;
          foreach($this->event->getGroupSlugs() as $slug) {
            if ($slug == $group->getSlug()) {
              $foundIt= true;
            }
          }
          if (!$foundIt) {
            $include = false;
          }
        }

        if ($include) {
          $this->groups[] = $group;
        }

    }
    $this->built = true;
  }

  public function get() {
    if (!$this->built) {
      $this->build();
    }
    return $this->groups;
  }

}
