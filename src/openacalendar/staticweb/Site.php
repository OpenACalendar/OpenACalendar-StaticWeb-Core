<?php


namespace openacalendar\staticweb;


use openacalendar\staticweb\config\Config;
use openacalendar\staticweb\config\ConfigLoaderIni;
use openacalendar\staticweb\data\DataLoaderIni;
use openacalendar\staticweb\dataerrors\DataErrorTwoEventsHaveSameSlugs;
use openacalendar\staticweb\dataerrors\DataErrorTwoGroupsHaveSameSlugs;
use openacalendar\staticweb\dataerrors\DataErrorEndBeforeStart;
use openacalendar\staticweb\datawarnings\DataWarningEventHasNoSlug;
use openacalendar\staticweb\datawarnings\DataWarningGroupHasNoSlug;
use openacalendar\staticweb\models\Event;
use openacalendar\staticweb\models\Group;
use openacalendar\staticweb\filters\EventFilter;
use openacalendar\staticweb\filters\GroupFilter;
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

	function __construct(Container $app, $dir)
	{
		$this->app = $app;
		$this->dir = $dir;
		$this->config = new Config();

		foreach(array(
			New ConfigLoaderIni($this->app),
				) as $loader) {
			if ($loader->isLoadableConfigInSite($this)) {
				$loader->loadConfigInSite($this->config, $this);
			}
		}

		$this->defaultCountry = $this->app['staticdatahelper']->getCountry($this->config->defaultCountry);
		// TODO error if null
		$this->defaultTimeZone = $this->app['staticdatahelper']->getTimeZone($this->config->defaultTimeZone);
		// TODO error if null or not in country

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

	protected $dataErrors = array();
	protected $dataWarnings = array();

	protected $events = array();
	protected $groups = array();

	function load() {

		$loaders = array(
			new DataLoaderIni($this->app),
		);

		foreach(scandir($this->dir . DIRECTORY_SEPARATOR. "data") as $fileName) {
			if ($fileName != "." && $fileName != '..') {

				foreach($loaders as $loader) {
					if ($loader->isLoadableDataInSite($this, $fileName)) {
						$out = $loader->loadDataInSite($this, $fileName);
						if (is_a($out, 'openacalendar\staticweb\dataerrors\BaseDataError')) {
							$this->dataErrors[] = $out;
						} else if (is_a($out, 'openacalendar\staticweb\models\Event')) {
							$this->addEvent($out);
						} else if (is_a($out, 'openacalendar\staticweb\models\Group')) {
							$this->addGroup($out);
						}
					}
				}

			}
		}

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

	protected function addEvent(Event $event) {
		if (!$event->getSlug()) {
			$this->dataWarnings[] = new DataWarningEventHasNoSlug();
			$event->createSlug();
		}
		foreach($this->events as $existingEvent) {
			if ($existingEvent->getSlug() == $event->getSlug()) {
				$this->dataErrors[] = new DataErrorTwoEventsHaveSameSlugs();
			}
		}
		if ($event->getStart()->getTimestamp() > $event->getEnd()->getTimestamp()) {
			$this->dataErrors[] = new DataErrorEndBeforeStart();
		}
		$this->events[] = $event;
	}

	protected function addGroup(Group $group) {
		if (!$group->getSlug()) {
			$this->dataWarnings[] = new DataWarningGroupHasNoSlug();
			$group->createSlug();
		}
		foreach($this->groups as $existingGroups) {
			if ($existingGroups->getSlug() == $group->getSlug()) {
				$this->dataErrors[] = new DataErrorTwoGroupsHaveSameSlugs();
			}
		}
		$this->groups[] = $group;
	}


	function write($outDir) {

		if (!$this->isLoaded) {
			$this->load();
		}

		$twigHelper = new TwigHelper($this);
		$twig = $twigHelper->getTwig();

		$outFolder = new OutFolder($outDir);

		// General Data
		$data = array(
			'allEvents'=>$this->events,
			'allGroups'=>$this->groups,
			'config'=>$this->config,
		);


		$eventsCurrentOrFutureFilter = new EventFilter($this, $this->app);
		$eventsCurrentOrFutureFilter->setPresentOrFutureOnly(true);
		$eventsCurrentOrFuture = $eventsCurrentOrFutureFilter->get();

		// Index
		$outFolder->addFileContents('','index.html', $twig->render('index.html.twig', array_merge($data, array(
			'events'=>$eventsCurrentOrFuture,
		))));

		// Event pages
		$outFolder->addFileContents('event','index.html',$twig->render('eventlist/index.html.twig', array_merge($data, array(
			'events'=>$eventsCurrentOrFuture,
		))));

		$outFolder->addFileContents('event','all.html',$twig->render('eventlist/all.html.twig', array_merge($data, array(
		))));

		foreach($this->events as $event) {
			$groupFilter = new GroupFilter($this, $this->app);
			$groupFilter->setEvent($event);
			$outFolder->addFileContents('event'.DIRECTORY_SEPARATOR.$event->getSlug(),'index.html',$twig->render('event/index.html.twig', array_merge($data, array(
				'event'=>$event,
				'groups'=>$groupFilter->get(),
			))));
		}

		// Group pages
		$x = new \openacalendar\staticweb\writecomponents\GroupWriteComponent($this->app, $this, $outFolder, $twigHelper);
		$x->write();
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
	public function getDataErrors()
	{
		if (!$this->isLoaded) {
			$this->load();
		}
		return $this->dataErrors;
	}

	/**
	 * @return array
	 */
	public function getDataWarnings()
	{
		if (!$this->isLoaded) {
			$this->load();
		}
		return $this->dataWarnings;
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
