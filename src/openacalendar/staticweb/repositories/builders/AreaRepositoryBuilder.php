<?php

namespace openacalendar\staticweb\repositories\builders;

use openacalendar\staticweb\models\CountryModel;
use openacalendar\staticweb\models\AreaModel;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class AreaRepositoryBuilder extends BaseRepositoryBuilder
{

    protected $orderBy = " title ";
    protected $orderDirection = " ASC ";

    protected $country;

    public function setCountry(CountryModel $country)
    {
        $this->country = $country;
    }

    /** @var AreaModel **/
    protected $parentArea;

    public function setParentArea(AreaModel $area) {
        $this->parentArea = $area;
    }

    protected $noParentArea = false;

    public function setNoParentArea($noParentArea) {
        $this->noParentArea = $noParentArea;
    }


    protected function build()
    {
        $this->select[] = 'area_information.*';


        if ($this->country) {
            $this->where[] = " area_information.country_id = :country_id ";
            $this->params['country_id'] = $this->country->getId();
        }

        if ($this->noParentArea) {
            $this->where[] = ' area_information.parent_area_id IS null ';
        } else if ($this->parentArea) {
            $this->where[] =  " area_information.parent_area_id = :parent_id ";
            $this->params['parent_id'] = $this->parentArea->getId();
        }

    }

    protected function buildStat()
    {

        $sql = "SELECT " . implode(",", $this->select) . " FROM area_information " .
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
            $area = new AreaModel();
            $area->setFromDataBaseRow($data);
            $results[] = $area;
        }
        return $results;


    }

}