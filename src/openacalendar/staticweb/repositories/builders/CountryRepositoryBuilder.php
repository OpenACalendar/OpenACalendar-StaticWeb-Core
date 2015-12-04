<?php

namespace openacalendar\staticweb\repositories\builders;

use openacalendar\staticweb\models\CountryModel;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class CountryRepositoryBuilder extends BaseRepositoryBuilder
{

    protected $orderBy = " title ";
    protected $orderDirection = " ASC ";


    protected $hasEventsOnly = false;

    /**
     * @param boolean $hasEventsOnly
     */
    public function setHasEventsOnly($hasEventsOnly)
    {
        $this->hasEventsOnly = $hasEventsOnly;
    }

    protected function build()
    {
        $this->select[] = 'country.*';

        if ($this->hasEventsOnly) {
            $this->joins[] =  " JOIN event_information AS event_information ON event_information.country_id = country.id ";
        }

    }

    protected function buildStat()
    {

        $sql = "SELECT " . implode(",", $this->select) . " FROM country " .
            implode(" ", $this->joins) .
            ($this->where ? " WHERE " . implode(" AND ", $this->where) : "") .
            " GROUP BY country.id ORDER BY  " . $this->orderBy . " " . $this->orderDirection . ($this->limit > 0 ? " LIMIT " . $this->limit : "");
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
            $event = new CountryModel();
            $event->setFromDataBaseRow($data);
            $results[] = $event;
        }
        return $results;


    }

}