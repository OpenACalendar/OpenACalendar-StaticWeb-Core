<?php

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class ConfigLoadIni1Test extends PHPUnit_Framework_TestCase {

	function testEmptyConfig() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'siteEmpty');

		$defaultConfig = new \openacalendar\staticweb\config\Config();

		$this->assertEquals($defaultConfig->title, $site->getConfig()->title);
		$this->assertEquals($defaultConfig->theme, $site->getConfig()->theme);
		$this->assertEquals($defaultConfig->defaultTimeZone, $site->getConfig()->defaultTimeZone);
		$this->assertEquals($defaultConfig->defaultCountry, $site->getConfig()->defaultCountry);
		$this->assertEquals($defaultConfig->baseURL, $site->getConfig()->baseURL);
	}

	function testTitle() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'siteTitle');

		$defaultConfig = new \openacalendar\staticweb\config\Config();

		$this->assertEquals('TEST', $site->getConfig()->title);
		$this->assertEquals($defaultConfig->theme, $site->getConfig()->theme);
		$this->assertEquals($defaultConfig->defaultTimeZone, $site->getConfig()->defaultTimeZone);
		$this->assertEquals($defaultConfig->defaultCountry, $site->getConfig()->defaultCountry);
		$this->assertEquals($defaultConfig->baseURL, $site->getConfig()->baseURL);

	}

	function testBaseURL() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'siteBaseURL');

		$defaultConfig = new \openacalendar\staticweb\config\Config();

		$this->assertEquals($defaultConfig->title, $site->getConfig()->title);
		$this->assertEquals($defaultConfig->theme, $site->getConfig()->theme);
		$this->assertEquals($defaultConfig->defaultTimeZone, $site->getConfig()->defaultTimeZone);
		$this->assertEquals($defaultConfig->defaultCountry, $site->getConfig()->defaultCountry);
		$this->assertEquals('/events/', $site->getConfig()->baseURL);
	}

	function testValid() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'siteValidCountryTimeZone');
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
		$this->assertEquals('openacalendar\staticweb\errors\ConfigErrorInvalidDefaultCountry', get_class($error));


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
		$this->assertEquals('openacalendar\staticweb\errors\ConfigErrorInvalidDefaultTimeZone', get_class($error));


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
		$this->assertEquals('openacalendar\staticweb\errors\ConfigErrorInvalidDefaultTimeZoneForDefaultCountry', get_class($error));


	}
}
