<?php

namespace openacalendar\staticweb\data;

use openacalendar\staticweb\errors\BaseError;
use openacalendar\staticweb\models\Event;
use openacalendar\staticweb\models\Group;
use openacalendar\staticweb\warnings\BaseWarning;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class DataLoadResult
{

	protected $errors = array();

	protected $warnings = array();

	protected $defaults = array();

	public function addDefault($item) {
		$this->defaults[] = $item;
	}

	public function addError(BaseError $error) {
		$this->errors[] = $error;
	}

	public function addWarning(BaseWarning $warning) {
		$this->warnings[] = $warning;
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return array
	 */
	public function getWarnings()
	{
		return $this->warnings;
	}

	/**
	 * @return array
	 */
	public function getDefaults()
	{
		return $this->defaults;
	}



}
