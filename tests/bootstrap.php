<?php

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Pimple\Container;

date_default_timezone_set('UTC');

define ('APP_ROOT_DIR', realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);

function autoload($class) {
	require_once APP_ROOT_DIR.'src' .DIRECTORY_SEPARATOR.str_replace("\\", DIRECTORY_SEPARATOR, $class).'.php';
}
spl_autoload_register('autoload');

$app = new Container();
$app['timesource'] = new \openacalendar\staticweb\TimeSource();

