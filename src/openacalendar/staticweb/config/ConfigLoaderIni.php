<?php


namespace openacalendar\staticweb\config;

use openacalendar\staticweb\Site;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class ConfigLoaderIni extends BaseConfigLoader {

	function isLoadableConfigInSite(Site $site)
	{
		$file = $site->getDir().DIRECTORY_SEPARATOR."config.ini";
		return file_exists($file) && is_readable($file);
	}

	function loadConfigInSite(Config $config, Site $site)
	{

		$file = $site->getDir().DIRECTORY_SEPARATOR."config.ini";
		$data = parse_ini_file($file);

		if (isset($data['theme']) && $data['theme']) {
			$config->theme = $data['theme']; // TODO check valid
		}
		if (isset($data['title']) && $data['title']) {
			$config->title = $data['title'];
		}
		if (isset($data['default_country']) && $data['default_country']) {
			$config->defaultCountry = $data['default_country']; // TODO check valid
		}
		if (isset($data['default_timezone']) && $data['default_timezone']) {
			$config->defaultTimeZone = $data['default_timezone']; // TODO check valid
		}
		if (isset($data['base_url']) && $data['base_url']) {
			$config->baseURL = $data['base_url']; 
		}

	}
}
