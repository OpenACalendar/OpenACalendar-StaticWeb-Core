<?php

namespace openacalendar\staticweb\data;

use openacalendar\staticweb\errors\DataErrorInvalidCountry;
use openacalendar\staticweb\errors\DataErrorInvalidTimeZone;
use openacalendar\staticweb\errors\DataErrorInvalidTimeZoneForCountry;
use openacalendar\staticweb\models\Area;
use openacalendar\staticweb\models\Event;
use openacalendar\staticweb\models\Group;
use openacalendar\staticweb\models\DefaultTimeZone;
use openacalendar\staticweb\repositories\EventRepository;
use openacalendar\staticweb\repositories\GroupRepository;
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

	function  isLoadableDefaultData($filename, $folder='', $defaults=array())
	{
		return substr($filename, -4) == '.ini' && $filename == 'data.ini';
	}

	function  isLoadableNonDefaultData($filename, $folder='', $defaults=array())
	{
		return substr($filename, -4) == '.ini' && $filename != 'data.ini';
	}

	function loadData($filename, $folder='', $defaults=array())
	{

		$isDefault = ($filename == 'data.ini');

		$data = parse_ini_file($this->siteContainer['site']->getDir().DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$filename, true);

		$out = new DataLoadResult();

        $group = null;

        if (isset($data['group'])) {

            $group = new Group();

            if (isset($data['group']['slug']) && $data['group']['slug']) {
                $group->setSlug($data['group']['slug']);
            }

            $group->setTitle($data['group']['title']);

            if (isset($data['group']['description']) && $data['group']['description']) {
                $group->setDescription($data['group']['description']);
            }

            if (isset($data['group']['url']) && $data['group']['url']) {
                if (filter_var($data['group']['url'], FILTER_VALIDATE_URL)) {
                    $group->setUrl($data['group']['url']);
                } else {
                    // TODO warn!
                }
            }

            $this->siteContainer['site']->addGroup($group);
            if ($isDefault) {
                $out->addDefault($group);
            }
        }

        if (isset($data['event'])) {

			$event = new Event();
            $groupsForEvent = $group ? array($group) : array();

			$event->setCountry($this->siteContainer['site']->getDefaultCountry());
			$event->setTimeZone($this->siteContainer['site']->getDefaultTimeZone());

			if (isset($data['event']['slug']) && $data['event']['slug']) {
				$event->setSlug($data['event']['slug']);
			}

            foreach($defaults as $default) {
                if (is_a($default,'openacalendar\staticweb\models\Group')) {
                    $groupsForEvent[] = $default;
                } else if (is_a($default, 'openacalendar\staticweb\models\Country')) {
                    $event->setCountry($default);
                } else if (is_a($default, 'openacalendar\staticweb\models\Area')) {
                    $event->setArea($default);
                } else if (is_a($default, 'openacalendar\staticweb\models\DefaultTimeZone')) {
                    $event->setTimeZone($default->getCode());
                }
            }

			$event->setTitle($data['event']['title']);

			$event->setStart($data['event']['start']);
			$event->setEnd($data['event']['end']);

			if (isset($data['event']['description']) && $data['event']['description']) {
				$event->setDescription($data['event']['description']);
			}

            if (isset($data['event']['url']) && $data['event']['url']) {
                if (filter_var($data['event']['url'], FILTER_VALIDATE_URL)) {
                    $event->setUrl($data['event']['url']);
                } else {
                    // TODO warn!
                }
            }

			if (isset($data['event']['country']) && $data['event']['country']) {
				$country = $this->siteContainer['countryrepository']->loadByHumanInput($data['event']['country']);
				if (!$country) {
					$out->addError(new DataErrorInvalidCountry());
					return $out;
				}
				$event->setCountry($country);
			}
			if (isset($data['event']['timezone']) && $data['event']['timezone']) {
				$timezone =  $data['event']['timezone'];
				if (!$this->siteContainer['countryrepository']->isTimeZoneValid($timezone)) {
					$out->addError(new DataErrorInvalidTimeZone());
					return $out;
				}
				$event->setTimeZone($timezone);
			}

			if (isset($data['event']['group_slug']) && $data['event']['group_slug']) {
                $groupSpeccedInEvent = $this->siteContainer['grouprepository']->loadBySlug($data['event']['group_slug']);;
                if (!$groupSpeccedInEvent) {
                    // TODO error
                } else {
                    $groupsForEvent[] = $groupSpeccedInEvent;
                }
			}

            if (is_a($event->getCountry(),'openacalendar\staticweb\models\Country') && $event->getTimeZone()) {
                if (!$event->getCountry()->hasTimeZone($event->getTimeZone())) {
                    $out->addError(new DataErrorInvalidTimeZoneForCountry());
                    return $out;
                }
            }


            $this->siteContainer['site']->addEvent($event);
            foreach($groupsForEvent as $groupSpeccedInEvent) {
                $this->siteContainer['grouprepository']->addEventToGroup($event, $groupSpeccedInEvent);
            }

		}

        if (isset($data['area']) && isset($data['area']['slug'])) {
            $area = new Area();
            $area->setSlug($data['area']['slug']);
            $area->setTitle(isset($data['area']['title']) ? $data['area']['title'] : $data['area']['slug']);
            $area->setCountry($this->siteContainer['site']->getDefaultCountry());
            foreach($defaults as $default) {
                if (is_a($default, 'openacalendar\staticweb\models\Country')) {
                    $area->setCountry($default);
                }
            }
            // TODO also have to look for country in $out->defaults
            $this->siteContainer['site']->addArea($area);
            if ($isDefault) {
                $out->addDefault($area);
            }
        }

        if ($isDefault) {

            if (isset($data['country']) && isset($data['country']['code'])) {
                $country = $this->siteContainer['countryrepository']->loadByHumanInput($data['country']['code']);
                if (!$country) {
                    $out->addError(new DataErrorInvalidCountry());
                    return $out;
                }
                $out->addDefault($country);
            }

            if (isset($data['timezone']) && isset($data['timezone']['timezone'])) {
                $timezone = new DefaultTimeZone($data['timezone']['timezone']);
                // TODO check exisits!
                if (!$timezone) {
                    $out->addError(new DataErrorInvalidTimeZone());
                    return $out;
                }
                $out->addDefault($timezone);
            }

        }

        return $out;
	}

}
