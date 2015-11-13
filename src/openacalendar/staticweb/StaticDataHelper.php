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

	protected $countries;

	protected $timeZones;

	protected function load() {

		$this->countries = array();

		foreach(explode("\n", file_get_contents(APP_ROOT_DIR.'/staticdata/iso3166.tab')) as $line) {
			if ($line && substr($line, 0,1) != '#') {
				$bits = explode("\t", $line) ;
				$this->countries[strtolower($bits[0])] = new Country($bits[0], $bits[1]);
			}
		}

		$this->timeZones = array();

		foreach(explode("\n", file_get_contents(APP_ROOT_DIR.'/staticdata/zone.tab')) as $line) {
			if ($line && substr($line, 0,1) != '#') {
				$bits = explode("\t", $line);

				if (!isset($this->timeZones[strtolower($bits[2])])) {
					$this->timeZones[strtolower($bits[2])] = new TimeZone($bits[2]);
				}

				$this->countries[strtolower($bits[0])]->addTimeZone($this->timeZones[strtolower($bits[2])]);
			}
		}


	}

	public function getCountries() {
		if (!$this->countries) {
			$this->load();
		}
		return array_values($this->countries);
	}

	public function getTimeZones() {
		if (!$this->timeZones) {
			$this->load();
		}
		return array_values($this->timeZones);
	}

	public function getCountry($value) {
		if (!$this->countries) {
			$this->load();
		}
		if (isset($this->countries[strtolower($value)])) {
			return $this->countries[strtolower($value)];
		}
		return null;
	}

	public function getTimeZone($value) {
		if (!$this->timeZones) {
			$this->load();
		}
		if (isset($this->timeZones[strtolower($value)])) {
			return $this->timeZones[strtolower($value)];
		}
		return null;
	}

}

