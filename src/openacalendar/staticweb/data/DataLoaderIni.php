<?php

namespace openacalendar\staticweb\data;

use openacalendar\staticweb\errors\DataErrorInvalidCountry;
use openacalendar\staticweb\errors\DataErrorInvalidTimeZone;
use openacalendar\staticweb\errors\DataErrorInvalidTimeZoneForCountry;
use openacalendar\staticweb\models\Event;
use openacalendar\staticweb\models\Group;
use openacalendar\staticweb\Site;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class DataLoaderIni extends  BaseDataLoader {

	function  isLoadableDataInSite(Site $site, $filename)
	{
		return substr($filename, -4) == '.ini';
	}

	function loadDataInSite(Site $site, $filename)
	{
		$data = parse_ini_file($site->getDir().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$filename, true);

		if (isset($data['event'])) {

			$event = new Event();

			$event->setCountry($site->getDefaultCountry());
			$event->setTimeZone($site->getDefaultTimeZone());

			if (isset($data['event']['slug']) && $data['event']['slug']) {
				$event->setSlug($data['event']['slug']);
			}

			$event->setTitle($data['event']['title']);

			$event->setStart($data['event']['start']);
			$event->setEnd($data['event']['end']);

			if (isset($data['event']['description']) && $data['event']['description']) {
				$event->setDescription($data['event']['description']);
			}

			if (isset($data['event']['country']) && $data['event']['country']) {
				$country = $this->app['staticdatahelper']->getCountry($data['event']['country']);
				if (!$country) {
					return new DataErrorInvalidCountry();
				}
				$event->setCountry($country);
			}
			if (isset($data['event']['timezone']) && $data['event']['timezone']) {
				$timezone = $this->app['staticdatahelper']->getTimeZone($data['event']['timezone']);
				if (!$timezone) {
					return new DataErrorInvalidTimeZone();
				}
				if (is_a($event->getCountry(),'openacalendar\staticweb\models\Country')) {
					if (!$event->getCountry()->hasTimeZone($timezone)) {
						return new DataErrorInvalidTimeZoneForCountry();
					}
				}
				$event->setTimeZone($timezone);
			}

			if (isset($data['event']['group_slug']) && $data['event']['group_slug']) {
				$event->addGroupSlug($data['event']['group_slug']);
			}



			return $event;
		}

		if (isset($data['group'])) {

			$group = new Group();

			if (isset($data['group']['slug']) && $data['group']['slug']) {
				$group->setSlug($data['group']['slug']);
			}

			$group->setTitle($data['group']['title']);

			if (isset($data['group']['description']) && $data['group']['description']) {
				$group->setDescription($data['group']['description']);
			}

			return $group;
		}
	}

}
