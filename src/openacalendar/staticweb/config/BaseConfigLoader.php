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
abstract class  BaseConfigLoader {

	abstract function isLoadableConfigInSite(Site $site);

	abstract function loadConfigInSite(Config $config, Site $site);

}
