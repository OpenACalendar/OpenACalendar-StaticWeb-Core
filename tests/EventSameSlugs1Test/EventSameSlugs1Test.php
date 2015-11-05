<?php

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class EventSameSlugs1Test extends PHPUnit_Framework_TestCase {

	function test1() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'site');
		$site->load();

		$warnings = $site->getDataWarnings();
		$this->assertEquals(0, count($warnings));

		$errors = $site->getDataErrors();
		$this->assertEquals(1, count($errors));
		$error = $errors[0];
		$this->assertEquals('openacalendar\staticweb\dataerrors\DataErrorTwoEventsHaveSameSlugs', get_class($error));



	}

}
