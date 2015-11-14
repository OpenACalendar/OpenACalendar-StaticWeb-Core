<?php
use openacalendar\staticweb\config\Config;
use openacalendar\staticweb\twig\extensions\InternalLinkHelper;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class InternalLinkHelperTest extends PHPUnit_Framework_TestCase
{


	function internalLinkDataProvider() {
		return array(
			array("/","cat.html","/cat.html"),
			array("/","event/cat.html","/event/cat.html"),
			array("/","/event/cat.html","/event/cat.html"),
			array("","cat.html","/cat.html"),
			array("","event/cat.html","/event/cat.html"),
			array("","/event/cat.html","/event/cat.html"),
			array("/calendar","cat.html","/calendar/cat.html"),
			array("/calendar","event/cat.html","/calendar/event/cat.html"),
			array("/calendar","/event/cat.html","/calendar/event/cat.html"),
			array("/calendar/","cat.html","/calendar/cat.html"),
			array("/calendar/","event/cat.html","/calendar/event/cat.html"),
			array("/calendar/","/event/cat.html","/calendar/event/cat.html"),
		);
	}

	/**
	 * @dataProvider internalLinkDataProvider
	 */
	function testInternalLink($baseurl, $in, $out) {
		$config = new Config();
		$config->baseURL = $baseurl;
		$lh = new InternalLinkHelper($config);
		$this->assertEquals($out, $lh->internalLink($in));
	}


	function internalLinkToDirDataProvider() {
		return array(
			array("", false, "/","/"),
			array("", true, "/","/index.html"),
			array("/", false, "/","/"),
			array("/", true, "/","/index.html"),
			array("/calendar", false, "/","/calendar/"),
			array("/calendar", true, "/","/calendar/index.html"),
			array("", false, "/event","/event/"),
			array("", true, "/event","/event/index.html"),
			array("/", false, "/event","/event/"),
			array("/", true, "/event","/event/index.html"),
			array("/calendar", false, "/event","/calendar/event/"),
			array("/calendar", true, "/event","/calendar/event/index.html"),
		);
	}

	/**
	 * @dataProvider internalLinkToDirDataProvider
	 */
	function testInternalLinkToDir($baseurl, $internalLinkToDirAppendDirectoryIndex, $in, $out) {
		$config = new Config();
		$config->baseURL = $baseurl;
		$config->internalLinkToDirAppendDirectoryIndex = $internalLinkToDirAppendDirectoryIndex;
		$lh = new InternalLinkHelper($config);
		$this->assertEquals($out, $lh->internalLinkToDir($in));
	}



}