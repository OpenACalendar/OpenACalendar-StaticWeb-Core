<?php

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class GroupNoSlug1Test extends PHPUnit_Framework_TestCase {

	function test1() {
		global $app;

		$site = new \openacalendar\staticweb\Site($app, __DIR__.DIRECTORY_SEPARATOR.'site');
		$site->load();

		$warnings = $site->getWarnings();
		$this->assertEquals(1, count($warnings));
		$warning = $warnings[0];
		$this->assertEquals('openacalendar\staticweb\warnings\DataWarningGroupHasNoSlug', get_class($warning));

		$errors = $site->getErrors();
		$this->assertEquals(0, count($errors));



	}

}
