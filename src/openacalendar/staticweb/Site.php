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
use openacalendar\staticweb\repositories\CountryRepository;
use openacalendar\staticweb\repositories\EventRepository;
use openacalendar\staticweb\repositories\GroupRepository;
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
	protected  $siteContainer;

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
        // Copy general app container and make a container specifically for this site.
        $this->siteContainer = new Container();
        $this->siteContainer['timesource'] = $app['timesource'];
        $this->siteContainer['lesscss'] = $app['lesscss'];
        $this->siteContainer['site'] = $this;
        $this->dir = $dir;
        $this->config = new Config();

        $anyConfigFound = false;

		foreach(array(
			New ConfigLoaderIni($this->siteContainer),
				) as $loader) {
			if ($loader->isLoadableConfigInSite($this)) {
				$loader->loadConfigInSite($this->config, $this);
                $anyConfigFound = true;
			}
		}

        if (!$anyConfigFound) {
            $this->errors[] = new ConfigErrorNotFound();
        }

        $this->siteContainer['databasehelper'] = new DataBaseHelper();

        $staticDataHelper = new \openacalendar\staticweb\StaticDataHelper($this->siteContainer);
        $staticDataHelper->load();

        $this->siteContainer['eventrepository'] = new EventRepository($this->siteContainer);
        $this->siteContainer['grouprepository'] = new GroupRepository($this->siteContainer);
        $this->siteContainer['countryrepository'] = new CountryRepository($this->siteContainer);


		$this->defaultCountry = $this->siteContainer['countryrepository']->loadByHumanInput($this->config->defaultCountry);
		if (!$this->defaultCountry) {
			$this->errors[] = new ConfigErrorInvalidDefaultCountry();
		}
		$this->defaultTimeZone = $this->config->defaultTimeZone;
		if (!$this->siteContainer['countryrepository']->isTimeZoneValid($this->defaultTimeZone)) {
			$this->errors[] = new ConfigErrorInvalidDefaultTimeZone();
		} else {
    		if ($this->defaultCountry  && !$this->defaultCountry->hasTimeZone($this->defaultTimeZone)) {
    			$this->errors[] = new ConfigErrorInvalidDefaultTimeZoneForDefaultCountry();
    		}
        }
		if (!in_array($this->config->theme, array('overthewall'))) {
			$this->errors[] = new ConfigErrorInvalidTheme();
		}

		$this->theme = new OverTheWallTheme($this->siteContainer);
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

	function load() {

        if ($this->isLoaded || $this->errors) {
            return;
        }

		$this->loadDir();

		$this->isLoaded = true;

	}

	protected function loadDir($dir = '', $defaults=array()) {

		$loaders = array(
			new DataLoaderIni($this->siteContainer),
		);

		$fullDir = $this->dir . DIRECTORY_SEPARATOR. "data".DIRECTORY_SEPARATOR.$dir;

		$ourDefaults = array();

		// Pass 1: "index" Files!
		foreach(scandir($fullDir) as $fileName) {
			if ($fileName != "." && $fileName != '..' && is_file($fullDir. DIRECTORY_SEPARATOR. $fileName)) {
				foreach ($loaders as $loader) {
					if ($loader->isLoadableDefaultData($fileName, $dir, $defaults)) {
						$out = $loader->loadData($fileName, $dir,array_merge($defaults, $ourDefaults));
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
					if ($loader->isLoadableNonDefaultData($fileName, $dir, array_merge($defaults, $ourDefaults))) {
						$out = $loader->loadData($fileName, $dir,array_merge($defaults, $ourDefaults) );
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

	public function addEvent(Event $event) {
		if (!$event->getSlug()) {
			$this->warnings[] = new DataWarningEventHasNoSlug();
			$event->createSlug();
		}
        if ($this->siteContainer['eventrepository']->loadBySlug($event->getSlug())) {
            $this->errors[] = new DataErrorTwoEventsHaveSameSlugs();
        }
		if ($event->getStart()->getTimestamp() > $event->getEnd()->getTimestamp()) {
			$this->errors[] = new DataErrorEndBeforeStart();
		}
		$this->siteContainer['eventrepository']->create($event);
	}

	public function addGroup(Group $group) {
		if (!$group->getSlug()) {
			$this->warnings[] = new DataWarningGroupHasNoSlug();
			$group->createSlug();
		}
        if ($this->siteContainer['grouprepository']->loadBySlug($group->getSlug())) {
            $this->errors[] = new DataErrorTwoGroupsHaveSameSlugs();
        }
		$this->siteContainer['grouprepository']->create($group);
	}


	function write($outDir) {

		if (!$this->isLoaded) {
			$this->load();
		}

		if ($this->errors) {
			throw new \Exception("Site Has Errors");
		}

		$this->theme->write($outDir);

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


}
