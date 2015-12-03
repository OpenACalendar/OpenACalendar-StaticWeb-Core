<?php

namespace openacalendar\staticweb\repositories;


use openacalendar\staticweb\models\Event;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class EventRepository extends BaseRepository
{

    public function loadBySlug($slug) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT event_information.* FROM event_information ".
            " WHERE event_information.slug =:slug ");
        $stat->execute(array( 'slug'=> $slug));
        if ($data = $stat->fetch(\PDO::FETCH_ASSOC)) {
            $group = new Event();
            $group->setFromDataBaseRow($data);
            return $group;
        }
    }

    public function create(Event $event) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("INSERT INTO event_information ".
            "(slug, summary, description, url, country_id, timezone, start_at,end_at )".
            " VALUES ".
            "(:slug, :summary,  :description, :url, :country_id, :timezone, :start_at, :end_at)");
        $stat->execute(array(
            'slug'=>$event->getSlug(),
            'summary'=>$event->getTitle(),
            'description'=>$event->getDescription(),
            'url'=>$event->getUrl(),
            'country_id'=>$event->getCountryId(),
            'timezone'=>$event->getTimezone(),
            'start_at'=>$event->getStart()->getTimestamp(),
            'end_at'=>$event->getEnd()->getTimestamp(),
        ));
        $event->setId($this->siteContainer['databasehelper']->getPDO()->lastInsertId());
    }

}
