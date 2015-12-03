<?php

namespace openacalendar\staticweb\repositories\builders;

use openacalendar\staticweb\models\Country;
use openacalendar\staticweb\models\Event;
use openacalendar\staticweb\models\Group;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class EventRepositoryBuilder extends BaseRepositoryBuilder
{

    protected $orderBy = " start_at ";
    protected $orderDirection = " ASC ";

    protected $country;

    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    protected $group;

    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /** @var integer * */
    protected $after = null;

    public function setAfter(\DateTime $a)
    {
        $this->after = $a;
        return $this;
    }

    public function setAfterNow()
    {
        $this->after = $this->siteContainer['timesource']->time();
        return $this;
    }


    protected function build()
    {
        $this->select[] = 'event_information.*';


        if ($this->country) {
            $this->where[] = " event_information.country_id = :country_id ";
            $this->params['country_id'] = $this->country->getId();
        }

        if ($this->group) {
            $this->joins[] =  " JOIN event_in_group AS event_in_group ON event_in_group.event_id = event_information.id ".
                " AND event_in_group.group_id = :group_id ";
            $this->params['group_id'] = $this->group->getId();
        }

        if ($this->after) {
            $this->where[] = ' event_information.end_at > :after';
            $this->params['after'] = $this->after;
        }

    }

    protected function buildStat()
    {

        $sql = "SELECT " . implode(",", $this->select) . " FROM event_information " .
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
            $event = new Event();
            $event->setFromDataBaseRow($data);
            $results[] = $event;
        }
        return $results;


    }

}