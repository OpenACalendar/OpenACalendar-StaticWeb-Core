<?php

namespace openacalendar\staticweb\data;

use openacalendar\staticweb\Site;
use openacalendar\staticweb\DataBaseHelper;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
abstract class BaseDataLoader {

	protected $siteContainer;


	function __construct($siteContainer)
	{
		$this->siteContainer = $siteContainer;
	}


	abstract function  isLoadableNonDefaultData($filename, $folder='', $defaults=array());

	abstract function  isLoadableDefaultData($filename, $folder='', $defaults=array());

	/** @var DataLoadResult **/
	abstract function loadData($filename, $folder='', $defaults=array());

}
