<?php


namespace openacalendar\staticweb;


use openacalendar\staticweb\config\Config;
use openacalendar\staticweb\config\ConfigLoaderIni;
use openacalendar\staticweb\data\DataLoaderIni;
use openacalendar\staticweb\errors\ConfigErrorInvalidDefaultCountry;
use openacalendar\staticweb\errors\ConfigErrorInvalidDefaultTimeZone;
use openacalendar\staticweb\errors\ConfigErrorInvalidDefaultTimeZoneForDefaultCountry;
use openacalendar\staticweb\errors\ConfigErrorInvalidTheme;
use openacalendar\staticweb\errors\ConfigErrorNotFound;
use openacalendar\staticweb\errors\DataErrorTwoEventsHaveSameSlugs;
use openacalendar\staticweb\errors\DataErrorTwoGroupsHaveSameSlugs;
use openacalendar\staticweb\errors\DataErrorEndBeforeStart;
use openacalendar\staticweb\themes\overthewall\OverTheWallTheme;
use openacalendar\staticweb\warnings\DataWarningEventHasNoSlug;
use openacalendar\staticweb\warnings\DataWarningGroupHasNoSlug;
use openacalendar\staticweb\models\Event;
use openacalendar\staticweb\models\Group;
use Pimple\Container;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class Site {

	/** @var  Container */
	protected  $app;

	protected $dir;

	/** @var  Config */
	protected $config;

	/** @var  Country */
	protected $defaultCountry;

	/** @var  TimeZone */
	protected $defaultTimeZone;

	/** @var BaseTheme */
	protected $theme;

	function __construct(Container $app, $dir)
	{
		$this->app = $app;
		$this->dir = $dir;
		$this->config = new Config();

        $anyConfigFound = false;

		foreach(array(
			New ConfigLoaderIni($this->app),
				) as $loader) {
			if ($loader->isLoadableConfigInSite($this)) {
				$loader->loadConfigInSite($this->config, $this);
                $anyConfigFound = true;
			}
		}

        if (!$anyConfigFound) {
            $this->errors[] = new ConfigErrorNotFound();
        }
		$this->defaultCountry = $this->app['staticdatahelper']->getCountry($this->config->defaultCountry);
		if (!$this->defaultCountry) {
			$this->errors[] = new ConfigErrorInvalidDefaultCountry();
		}
		$this->defaultTimeZone = $this->app['staticdatahelper']->getTimeZone($this->config->defaultTimeZone);
		if (!$this->defaultTimeZone) {
			$this->errors[] = new ConfigErrorInvalidDefaultTimeZone();
		}
		if ($this->defaultCountry && $this->defaultTimeZone && !$this->defaultCountry->hasTimeZone($this->defaultTimeZone)) {
			$this->errors[] = new ConfigErrorInvalidDefaultTimeZoneForDefaultCountry();
		}
		if (!in_array($this->config->theme, array('overthewall'))) {
			$this->errors[] = new ConfigErrorInvalidTheme();
		}

		$this->theme = new OverTheWallTheme($app);
	}

	/**
	 * @return Country
	 */
	public function getDefaultCountry()
	{
		return $this->defaultCountry;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultTimeZone()
	{
		return $this->defaultTimeZone;
	}
	
	protected $isLoaded = false;

	protected $errors = array();
	protected $warnings = array();

	protected $events = array();
	protected $groups = array();

	function load() {

        if ($this->isLoaded || $this->errors) {
            return;
        }

		$this->loadDir();

		usort($this->events, function($a, $b) {
			if ($a->getStart()->getTimeStamp() == $b->getStart()->getTimeStamp()) {
				return 0;
			} else if ($a->getStart()->getTimeStamp() > $b->getStart()->getTimeStamp()) {
				return 1;
			} else {
				return -1;
			}
		});

		usort($this->groups, function($a, $b) {
			return strcasecmp($a->getTitle(), $b->getTitle());
		});

		$this->isLoaded = true;

	}

	protected function loadDir($dir = '', $defaults=array()) {

		$loaders = array(
			new DataLoaderIni($this->app),
		);

		$fullDir = $this->dir . DIRECTORY_SEPARATOR. "data".DIRECTORY_SEPARATOR.$dir;
		
		$ourDefaults = array();

		// Pass 1: "index" Files!
		foreach(scandir($fullDir) as $fileName) {
			if ($fileName != "." && $fileName != '..' && is_file($fullDir. DIRECTORY_SEPARATOR. $fileName)) {
				foreach ($loaders as $loader) {
					if ($loader->isLoadableDefaultDataInSite($this, $fileName, $dir, $defaults)) {
						$out = $loader->loadDataInSite($this, $fileName, $dir,array_merge($defaults, $ourDefaults));
						foreach($out->getEvents() as $event) {
							$this->addEvent($event);
						}
						foreach($out->getGroups() as $group) {
							$this->addGroup($group);
						}
						foreach($out->getErrors() as $error) {
							$this->errors[] = $error;
						}
						foreach($out->getWarnings() as $warning) {
							$this->warnings[] = $warning;
						}
						foreach($out->getDefaults() as $item) {
							$ourDefaults[] = $item;
						}
					}
				}
			}
		}

		// TODO do we have a default in this dir that clashes with one further up? eg country?

		// Pass 2: Files that are not "index" files!
		foreach(scandir($fullDir) as $fileName) {
			if ($fileName != "." && $fileName != '..' && is_file($fullDir. DIRECTORY_SEPARATOR. $fileName)) {
				foreach ($loaders as $loader) {
					if ($loader->isLoadableNonDefaultDataInSite($this, $fileName, $dir, array_merge($defaults, $ourDefaults))) {
						$out = $loader->loadDataInSite($this, $fileName, $dir,array_merge($defaults, $ourDefaults) );
						foreach($out->getEvents() as $event) {
							$this->addEvent($event);
						}
						foreach($out->getGroups() as $group) {
							$this->addGroup($group);
						}
						foreach($out->getErrors() as $error) {
							$this->errors[] = $error;
						}
						foreach($out->getWarnings() as $warning) {
							$this->warnings[] = $warning;
						}
					}
				}
			}
		}

		// Pass 3: Dirs!
		foreach(scandir($fullDir) as $fileName) {
			if ($fileName != "." && $fileName != '..' && is_dir($fullDir. DIRECTORY_SEPARATOR. $fileName)) {
				$this->loadDir($dir . DIRECTORY_SEPARATOR. $fileName,  array_merge($defaults, $ourDefaults));
			}
		}
	}

	protected function addEvent(Event $event) {
		if (!$event->getSlug()) {
			$this->warnings[] = new DataWarningEventHasNoSlug();
			$event->createSlug();
		}
		foreach($this->events as $existingEvent) {
			if ($existingEvent->getSlug() == $event->getSlug()) {
				$this->errors[] = new DataErrorTwoEventsHaveSameSlugs();
			}
		}
		if ($event->getStart()->getTimestamp() > $event->getEnd()->getTimestamp()) {
			$this->errors[] = new DataErrorEndBeforeStart();
		}
		$this->events[] = $event;
	}

	protected function addGroup(Group $group) {
		if (!$group->getSlug()) {
			$this->warnings[] = new DataWarningGroupHasNoSlug();
			$group->createSlug();
		}
		foreach($this->groups as $existingGroups) {
			if ($existingGroups->getSlug() == $group->getSlug()) {
				$this->errors[] = new DataErrorTwoGroupsHaveSameSlugs();
			}
		}
		$this->groups[] = $group;
	}


	function write($outDir) {

		if (!$this->isLoaded) {
			$this->load();
		}

		if ($this->errors) {
			throw new \Exception("Site Has Errors");
		}

		$this->theme->write($this, $outDir);

	}

	/**
	 * @return String
	 */
	public function getDir()
	{
		return $this->dir;
	}

	/**
	 * @return Config
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * @return boolean
	 */
	public function isIsLoaded()
	{
		return $this->isLoaded;
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		if (!$this->isLoaded) {
			$this->load();
		}
		return $this->errors;
	}

	/**
	 * @return array
	 */
	public function getWarnings()
	{
		if (!$this->isLoaded) {
			$this->load();
		}
		return $this->warnings;
	}

	/**
	 * @return array
	 */
	public function getEvents()
	{
		if (!$this->isLoaded) {
			$this->load();
		}
		return $this->events;
	}

	/**
	 * @return array
	 */
	public function getGroups()
	{
		if (!$this->isLoaded) {
			$this->load();
		}
		return $this->groups;
	}

}
