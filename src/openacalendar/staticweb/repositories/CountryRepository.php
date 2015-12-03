<?php

namespace openacalendar\staticweb\repositories;


use openacalendar\staticweb\models\Country;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class CountryRepository extends BaseRepository
{


    public function loadByHumanInput($something) {
        $country = $this->loadByTwoCharCode($something);
        if ($country) {
            return $country;
        }
        $country = $this->loadByTitle($something);
        if ($country) {
            return $country;
        }
    }

    public function loadByTwoCharCode($code) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT country.* FROM country ".
            " WHERE country.two_char_code =:code ");
        $stat->execute(array( 'code'=> strtoupper($code)));
        if ($data = $stat->fetch(\PDO::FETCH_ASSOC)) {
            $country = new Country();
            $country->setFromDataBaseRow($data);
            return $country;
        }
    }

    public function loadById($id) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT country.* FROM country ".
            " WHERE country.id =:id ");
        $stat->execute(array( 'id'=>$id));
        if ($data = $stat->fetch(\PDO::FETCH_ASSOC)) {
            $country = new Country();
            $country->setFromDataBaseRow($data);
            return $country;
        }
    }

    public function loadByTitle($title) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT country.* FROM country ".
            " WHERE country.title = :title ");
        $stat->execute(array( 'title'=>$title));
        if ($data = $stat->fetch(\PDO::FETCH_ASSOC)) {
            $country = new Country();
            $country->setFromDataBaseRow($data);
            return $country;
        }
    }

    public function isTimeZoneValid($timeZone) {
        $stat = $this->siteContainer['databasehelper']->getPDO()->prepare("SELECT country.* FROM country ");
        $stat->execute();
        while ($data = $stat->fetch(\PDO::FETCH_ASSOC)) {
            $country = new Country();
            $country->setFromDataBaseRow($data);
            if ($country->hasTimeZone($timeZone)) {
                return true;
            }
        }
        return false;
    }
}
