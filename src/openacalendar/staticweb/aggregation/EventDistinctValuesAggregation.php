<?php


namespace openacalendar\staticweb\aggregation;

use openacalendar\staticweb\filters\EventFilter;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class EventDistinctValuesAggregation {


	/** @var EventFilter */
	protected  $eventFilter;

	function __construct(EventFilter $eventFilter)
	{
		$this->eventFilter = $eventFilter;
	}

	function getDistinctCountries() {
		$out = array();

		foreach($this->eventFilter->get() as $event) {
			if ($event->getCountry() && !isset($out[$event->getCountry()->getCode()])) {
				$out[$event->getCountry()->getCode()] = $event->getCountry();
			}
		}

		return $out;
	}

}
