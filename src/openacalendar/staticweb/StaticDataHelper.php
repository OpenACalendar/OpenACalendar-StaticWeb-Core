<?php


namespace openacalendar\staticweb;

use openacalendar\staticweb\models\TimeZone;
use openacalendar\staticweb\models\Country;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class StaticDataHelper {

    protected $siteContainer;

    function __construct($siteContainer)
    {
        $this->siteContainer = $siteContainer;
    }


    public function load()
    {

        $countries = array();

        # Step - Load Countries
        foreach(explode("\n", file_get_contents(APP_ROOT_DIR.'/staticdata/iso3166.tab')) as $line) {
            if ($line && substr($line, 0,1) != '#') {
                $bits = explode("\t", $line) ;
                $countries[strtoupper($bits[0])] = array(
                    'Title'=>$bits[1],
                    'TimeZones'=>array(),
                );

            }
        }

        # Step - Load Timezones
        foreach(explode("\n", file_get_contents(APP_ROOT_DIR.'/staticdata/zone.tab')) as $line) {
            if ($line && substr($line, 0,1) != '#') {
                $bits = explode("\t", $line);
                $countries[strtoupper($bits[0])]['TimeZones'][] = $bits[2];
            }
        }

        # Step - Save to DB
        $statInsert = $this->siteContainer['databasehelper']->getPDO()->prepare("INSERT INTO country (two_char_code,title,timezones) ".
            "VALUES (:two_char_code,:title,:timezones)");
        foreach($countries as $code=>$countryData) {
            $statInsert->execute(array(
                'two_char_code'=>  strtoupper($code),
                'timezones'=>implode(",",$countryData['TimeZones']),
                'title'=>$countryData['Title'],
            ));
        }

    }

}

