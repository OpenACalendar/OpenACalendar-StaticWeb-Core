<?php

namespace openacalendar\staticweb\repositories\builders;

use openacalendar\staticweb\models\AreaModel;
use openacalendar\staticweb\models\CountryModel;
use openacalendar\staticweb\models\EventModel;
use openacalendar\staticweb\models\GroupModel;

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

    /** @var CountryModel */
    protected $country;

    public function setCountry(CountryModel $country)
    {
        $this->country = $country;
    }

    /** @var  AreaModel */
    protected $area;

    public function setArea(AreaModel $area)
    {
        $this->area = $area;
    }

    protected $group;

    public function setGroup(GroupModel $group)
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

        if ($this->area) {


            // We were doing
            // $this->joins[] = " LEFT JOIN cached_area_has_parent ON cached_area_has_parent.area_id = venue_information.area_id";
            // $this->where[] =  " (venue_information.area_id = :area_id OR  cached_area_has_parent.has_parent_area_id = :area_id )";
            // but then we got duplicates

            $areaids = array( $this->area->getId() );

            $this->statAreas = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT area_id FROM cached_area_has_parent WHERE has_parent_area_id=:id");
            $this->statAreas->execute(array('id'=>$this->area->getId()));
            while($d = $this->statAreas->fetch()) {
                $areaids[] = $d['area_id'];
            }

            $this->where[] =  "  event_information.area_id IN (".  implode(",", $areaids).")";

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
            $event = new EventModel();
            $event->setFromDataBaseRow($data);
            $results[] = $event;
        }
        return $results;


    }

}