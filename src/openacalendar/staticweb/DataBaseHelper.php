<?php

namespace openacalendar\staticweb;

/**
*
* @package Core
* @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
* @license http://ican.openacalendar.org/license.html 3-clause BSD
* @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
* @author James Baster <james@jarofgreen.co.uk>
*/
class DataBaseHelper
{
    
    /** @var \PDO **/
    protected $pdo;

    protected function createNewDataBase() {
        $tempfilename = tempnam(sys_get_temp_dir(), 'openacalendarstaticwebdb');
        $this->pdo = new \PDO("sqlite:".$tempfilename);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $contents = file_get_contents(APP_ROOT_DIR.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'create.sql');
        foreach(explode(";", $contents) as $content) {
            if (trim($content)) {
                $this->pdo->exec($content);
            }
        }

    }

    /** @return \PDO **/
    public function getPDO() {
        if (!$this->pdo) {
            $this->createNewDataBase();
        }
        return $this->pdo;
    }

}
