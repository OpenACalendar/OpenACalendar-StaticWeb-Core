<?php

namespace openacalendar\staticweb\repositories;


use openacalendar\staticweb\models\AreaModel;

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
            $area = new AreaModel();
            $area->setFromDataBaseRow($data);
            return $area;
        }
    }

    public function create(AreaModel $area) {
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

    /**
     *
     *
     */
    public function buildCacheAreaHasParent(AreaModel $area) {
        $statFirstArea = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT area_information.parent_area_id FROM area_information WHERE area_information.id=:id");
        // get first parent
        $areaParentID = null;
        $statFirstArea->execute(array('id'=>$area->getId()));
        $d = $statFirstArea->fetch();
        if ($d) {
            $areaParentID = $d['parent_area_id'];
        }

        $statInsertCache = $this->siteContainer['databasehelper']->getPDO()->prepare("INSERT INTO cached_area_has_parent(area_id,has_parent_area_id) VALUES (:area_id,:has_parent_area_id)");
        $statNextArea = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT area_information.parent_area_id FROM area_information WHERE area_information.id=:id");
        while($areaParentID) {
            // insert this parent into the cache
            $statInsertCache->execute(array('area_id'=>$area->getId(), 'has_parent_area_id'=>$areaParentID));

            // move up to next parent
            $statNextArea->execute(array('id'=>$areaParentID));
            $d = $statNextArea->fetch();
            if ($d) {
                $areaParentID = $d['parent_area_id'];
            } else {
                $areaParentID = null;
            }
        }

    }

}
