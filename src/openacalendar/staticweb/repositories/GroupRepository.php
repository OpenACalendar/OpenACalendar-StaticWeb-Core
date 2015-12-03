<?php

namespace openacalendar\staticweb\repositories;


use openacalendar\staticweb\models\Group;
use openacalendar\staticweb\models\Event;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class GroupRepository extends BaseRepository
{

    public function loadByHumanInput($something) {
        $group = $this->loadBySlug($something);
        if ($group) {
            return $group;
        }
    }

    public function loadBySlug($slug) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT group_information.* FROM group_information ".
            " WHERE group_information.slug =:slug ");
        $stat->execute(array( 'slug'=> $slug));
        if ($data = $stat->fetch(\PDO::FETCH_ASSOC)) {
            $group = new Group();
            $group->setFromDataBaseRow($data);
            return $group;
        }
    }

    public function create(Group $group) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("INSERT INTO group_information ".
            "(slug, title, description, url )".
            " VALUES ".
            "(:slug, :title,  :description, :url)");
        $stat->execute(array(
            'slug'=>$group->getSlug(),
            'title'=>$group->getTitle(),
            'description'=>$group->getDescription(),
            'url'=>$group->getUrl(),
        ));
        $group->setId($this->siteContainer['databasehelper']->getPDO()->lastInsertId());
    }

    public function addEventToGroup(Event $event, Group $group) {

        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT * FROM event_in_group WHERE group_id=:group_id AND ".
            " event_id=:event_id ");
        $stat->execute(array(
            'group_id'=>$group->getId(),
            'event_id'=>$event->getId(),
        ));
        if ($stat->fetch()) {
            return;
        }

        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("INSERT INTO event_in_group ".
            "(event_id, group_id )".
            " VALUES ".
            "(:event_id, :group_id)");
        $stat->execute(array(
            'event_id'=>$event->getId(),
            'group_id'=>$group->getId(),
        ));

    }

}
