<?php


namespace openacalendar\staticweb\tasks;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
abstract class BaseTask {


    protected  $siteContainer;

    public function __construct($siteContainer) {
        $this->siteContainer = $siteContainer;
    }

    abstract function run();

}
