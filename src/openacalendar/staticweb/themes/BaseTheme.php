<?php

namespace openacalendar\staticweb\themes;

use openacalendar\staticweb\Site;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
abstract class BaseTheme
{


	protected $app;

	function __construct($app)
	{
		$this->app = $app;
	}


	abstract function write(Site $site, $dir);

}
