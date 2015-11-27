<?php

namespace openacalendar\staticweb\models;

/**
*
* @package Core
* @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
* @license http://ican.openacalendar.org/license.html 3-clause BSD
* @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
* @author James Baster <james@jarofgreen.co.uk>
*/
class Group
{

    protected $slug;

    protected $title;

    protected $country;


    /**
    * @return mixed
    */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
    * @param mixed $slug
    */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function createSlug() {
        $this->slug = "noslug"; // TODO better - base on title
    }

    /**
    * @return mixed
    */
    public function getTitle()
    {
        return $this->title;
    }

    /**
    * @param mixed $title
    */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
    * @return mixed
    */
    public function getCountry()
    {
        return $this->country;
    }

    /**
    * @param mixed $country
    */
    public function setCountry($country)
    {
        $this->country = $country;
    }
    
}
