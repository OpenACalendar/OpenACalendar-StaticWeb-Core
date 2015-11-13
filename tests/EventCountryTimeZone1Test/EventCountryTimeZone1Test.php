<?php

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class EventCountryTimeZone1Test extends PHPUnit_Framework_TestCase {

	function testValid() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'siteValid');
		$site->load();

		$warnings = $site->getWarnings();
		$this->assertEquals(0, count($warnings));

		$errors = $site->getErrors();
		$this->assertEquals(0, count($errors));


	}

	function testInvalidCountry() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'siteInvalidCountry');
		$site->load();

		$warnings = $site->getWarnings();
		$this->assertEquals(0, count($warnings));

		$errors = $site->getErrors();
		$this->assertEquals(1, count($errors));
		$error = $errors[0];
		$this->assertEquals('openacalendar\staticweb\errors\DataErrorInvalidCountry', get_class($error));


	}

	function testInvalidTimeZone() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'siteInvalidTimeZone');
		$site->load();

		$warnings = $site->getWarnings();
		$this->assertEquals(0, count($warnings));

		$errors = $site->getErrors();
		$this->assertEquals(1, count($errors));
		$error = $errors[0];
		$this->assertEquals('openacalendar\staticweb\errors\DataErrorInvalidTimeZone', get_class($error));


	}

	function testInvalidTimeZoneForCountry() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'siteInvalidTimeZoneForCountry');
		$site->load();

		$warnings = $site->getWarnings();
		$this->assertEquals(0, count($warnings));

		$errors = $site->getErrors();
		$this->assertEquals(1, count($errors));
		$error = $errors[0];
		$this->assertEquals('openacalendar\staticweb\errors\DataErrorInvalidTimeZoneForCountry', get_class($error));


	}
}
