<?php

namespace openacalendar\staticweb\twig\extensions;
use openacalendar\staticweb\config\Config;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class InternalLinkHelper  extends \Twig_Extension {


	/** @var Config */
	protected $config;

	function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function getFunctions()
	{
		return array();
	}

	public function getFilters()
	{
		return array(
			'internalLink' => new \Twig_Filter_Method($this, 'internalLink', array()),
			'internalLinkToDir' => new \Twig_Filter_Method($this, 'internalLinkToDir', array()),
		);
	}

	public function internalLink($link) {

		if (substr($this->config->baseURL, -1) == '/' && substr($link, 0, 1) == '/') {
			return $this->config->baseURL . substr($link, 1);
		} else if (substr($this->config->baseURL, -1) != '/' && substr($link, 0, 1) != '/') {
			return $this->config->baseURL . '/' . $link;
		} else {
			return $this->config->baseURL . $link;
		}
	}

	public function internalLinkToDir($link) {

		if (substr($this->config->baseURL, -1) == '/' && substr($link, 0, 1) == '/') {
			$out = $this->config->baseURL . substr($link, 1);
		} else if (substr($this->config->baseURL, -1) != '/' && substr($link, 0, 1) != '/') {
			$out =  $this->config->baseURL . '/' . $link;
		} else {
			$out = $this->config->baseURL . $link;
		}
		if (substr($out, -1) != '/') {
			$out .='/';
		}
		if ($this->config->internalLinkToDirAppendDirectoryIndex) {
			$out .= 'index.html';
		}
		return $out;
	}

	public function getName()
	{
		return 'internallink';
	}

}

