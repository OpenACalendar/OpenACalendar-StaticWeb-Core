<?php

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'autoload.php';



$opts = getopt('',array('help','build','out:','site:','baseurl:','log:'));


if (isset($opts['help'])) {
	print "HELP PAGE\n";
	die();
}

$log = isset($opts['log']) ? strtolower(trim($opts['log'])) : 'default';
if ($log == 'none') {
    $app['log']->pushHandler(new Monolog\Handler\NullHandler());
} else if ($log == 'debug') {
    $app['log']->pushHandler(new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::DEBUG));
} else if ($log == 'info') {
    $app['log']->pushHandler(new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::INFO));
} else if ($log == 'warning') {
    $app['log']->pushHandler(new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::WARNING));
} else if ($log == 'error') {
    $app['log']->pushHandler(new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::ERROR));
} else {
    // this is the default level
    $app['log']->pushHandler(new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::NOTICE));
}


function setConfig(\openacalendar\staticweb\Site $site, $opts) {
	if (isset($opts['baseurl']) && $opts['baseurl']) {
		$site->getConfig()->baseURL = $opts['baseurl'];
	}
}

if (isset($opts['build'])) {
    if (!isset($opts['out']) || !$opts['out']) {
        print "Build but no out set?\n";
        die();
    }
    if (!isset($opts['site']) || !$opts['site']) {
        print "Build but no site set?\n";
        die();
    }

    $site = new \openacalendar\staticweb\Site($app, $opts['site']);
    setConfig($site, $opts);

    if ($site->getWarnings()) {
        print "Warnings:\n\n";
        foreach($site->getWarnings() as $warning) {
            print get_class($warning)."\n\n";
        }
    }

    if ($site->getErrors()) {
        print "ERRORS:\n\n";
        foreach($site->getErrors() as $error) {
            print get_class($error)."\n\n";
        }

        // Exit failure
        exit(1);
    } else {

        $site->write($opts['out']);

        // Exit Success
        exit(0);

    }


}
