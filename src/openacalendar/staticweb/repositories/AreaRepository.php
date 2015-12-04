<?php

namespace openacalendar\staticweb\repositories;


use openacalendar\staticweb\models\Area;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class AreaRepository extends BaseRepository
{

    public function loadBySlug($slug) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT area_information.* FROM area_information ".
            " WHERE area_information.slug =:slug ");
        $stat->execute(array( 'slug'=> $slug));
        if ($data = $stat->fetch(\PDO::FETCH_ASSOC)) {
            $area = new Area();
            $area->setFromDataBaseRow($data);
            return $area;
        }
    }

    public function create(Area $area) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("INSERT INTO area_information ".
            "(slug, title, country_id, parent_area_id)".
            " VALUES ".
            "(:slug, :title, :country_id, :parent_area_id)");
        $stat->execute(array(
            'slug'=>$area->getSlug(),
            'title'=>$area->getTitle(),
            'country_id'=>$area->getCountryId(),
            'parent_area_id'=>$area->getParentAreaId(),
        ));
        $area->setId($this->siteContainer['databasehelper']->getPDO()->lastInsertId());
    }

}
