<?php

namespace openacalendar\staticweb\models;

use openacalendar\staticweb\config\Config;

/**
 *
 *
 * @TODO this is only used for DefaultTimeZone. Rename to this to make clear.
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class TimeZone
{


    protected $code;

    function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }




}

