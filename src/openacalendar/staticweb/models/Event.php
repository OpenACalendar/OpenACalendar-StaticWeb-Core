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
class Event {

	protected $slug;

	protected $title;

	/** @var  /DateTime */
	protected $start;

	/** @var  /DateTime */
	protected $end;

	protected $country;

	protected $timeZone;

  protected $description;

	protected $groupSlugs = array();

	/**
	 * @return mixed
	 */
	public function getEnd()
	{
		return $this->end;
	}

	/**
	 * @param mixed $end
	 */
	public function setEnd($end)
	{
		if (is_string($end)) {
			$this->end = new \DateTime($end, new \DateTimeZone('UTC'));
		} else {
			$this->end = $end;
		}
	}

	/**
	 * @return mixed
	 */
	public function getStart()
	{
		return $this->start;
	}

	/**
	 * @param mixed $start
	 */
	public function setStart($start)
	{
		if (is_string($start)) {
			$this->start = new \DateTime($start, new \DateTimeZone('UTC'));
		} else {
			$this->start = $start;
		}
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param mixed $slug
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
	}


	public function createSlug() {
		$this->slug = "noslug"; // TODO better - base on title
	}

	/**
	 * @return mixed
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @param mixed $country
	 */
	public function setCountry($country)
	{
		$this->country = $country;
	}

	/**
	 * @return mixed
	 */
	public function getTimeZone()
	{
		return $this->timeZone;
	}

	/**
	 * @param mixed $timeZone
	 */
	public function setTimeZone($timeZone)
	{
		$this->timeZone = $timeZone;
	}

	public function addGroupSlug($groupSlug) {
		$this->groupSlugs[] = $groupSlug;
	}

	public function getGroupSlugs() {
		return $this->groupSlugs;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

}
