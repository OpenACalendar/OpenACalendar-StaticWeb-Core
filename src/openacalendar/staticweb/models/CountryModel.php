<?php

namespace openacalendar\staticweb\models;

use openacalendar\staticweb\config\Config;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class CountryModel
{

    protected $code;

    protected $title;

    protected $timeZones = array();

    public function setFromDataBaseRow($data) {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->two_char_code = $data['two_char_code'];
        $this->timezones = $data['timezones'];
    }


    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getTitle() {
        return $this->title;
    }
    public function getTwoCharCode() {
        return $this->two_char_code;
    }

    public function getTimezones() {
        return $this->timezones;
    }

    public function getTimezonesAsList() {
        return explode(",", $this->timezones);
    }

    public function hasTimeZone($timeZone) {
        foreach(explode(",", $this->timezones) as $hasTimeZone) {
            if (strtolower($hasTimeZone) == strtolower($timeZone)) {
                return true;
            }
        }
        return false;
    }

}

