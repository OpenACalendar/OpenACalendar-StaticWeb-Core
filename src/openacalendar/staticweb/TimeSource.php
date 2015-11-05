<?php


namespace openacalendar\staticweb;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class TimeSource {

	protected $now = null;

	public function getFormattedForDataBase() {
		$dt = new \DateTime('', new \DateTimeZone('UTC'));
		if ($this->now) $dt->setTimestamp(TimeSource::$now);
		return $dt->format("Y-m-d H:i:s");
	}



	public function time() {
		return $this->now ? $this->now : time();
	}

	/** @var \DateTime **/
	public function getDateTime() {
		$dt = new \DateTime('', new \DateTimeZone('UTC'));
		if ($this->now) $dt->setTimestamp($this->now);
		return $dt;
	}

	public function mock($year=2012, $month=1, $day=1, $hour=0, $minute=0, $second=0) {
		$dt = new \DateTime('', new \DateTimeZone('UTC'));
		$dt->setTime($hour, $minute, $second);
		$dt->setDate($year, $month, $day);
		$this->now = $dt->getTimestamp();
	}

	public function realTime() {
		$this->now = null;
	}

}