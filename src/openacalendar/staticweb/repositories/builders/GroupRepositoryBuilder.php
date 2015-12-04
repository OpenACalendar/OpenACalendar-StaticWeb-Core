<?php

namespace openacalendar\staticweb\repositories\builders;

use openacalendar\staticweb\models\GroupModel;
use openacalendar\staticweb\models\EventModel;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class GroupRepositoryBuilder extends BaseRepositoryBuilder
{

    protected $orderBy = " title ";
    protected $orderDirection = " ASC ";


    /** @var EventModel **/
    protected $event;

    public function setEvent(EventModel $event) {
        $this->event = $event;
    }

    protected function build()
    {
        $this->select[] = 'group_information.*';

        if ($this->event) {
            $this->joins[] =  " JOIN event_in_group AS event_in_group ON event_in_group.group_id = group_information.id ".
                " AND event_in_group.event_id = :event_id ";
            $this->params['event_id'] = $this->event->getId();
        }

    }

    protected function buildStat()
    {

        $sql = "SELECT " . implode(",", $this->select) . " FROM group_information " .
            implode(" ", $this->joins) .
            ($this->where ? " WHERE " . implode(" AND ", $this->where) : "") .
            " ORDER BY  " . $this->orderBy . " " . $this->orderDirection . ($this->limit > 0 ? " LIMIT " . $this->limit : "");
        $this->stat = $this->siteContainer['databasehelper']->getPDO()->prepare($sql);
        $this->stat->execute($this->params);

    }


    public function fetchAll()
    {

        $this->buildStart();
        $this->build();
        $this->buildStat();

        $results = array();
        while ($data = $this->stat->fetch()) {
            $event = new GroupModel();
            $event->setFromDataBaseRow($data);
            $results[] = $event;
        }
        return $results;


    }

}