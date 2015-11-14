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
class Country
{

	protected $code;

	protected $title;

	protected $timeZones = array();

	function __construct($code, $title)
	{
		$this->code = strtolower($code);
		$this->title = $title;
	}

	public function addTimeZone(TimeZone $timeZone) {
		$this->timeZones[] = $timeZone;
	}

	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return array
	 */
	public function getTimeZones()
	{
		return $this->timeZones;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	public function hasTimeZone(TimeZone $timeZone) {
		foreach($this->timeZones as $ourTimeZone) {
			if($ourTimeZone->getCode() == $timeZone->getCode()) {
				return true;
			}
		}
		return false;
	}


}

