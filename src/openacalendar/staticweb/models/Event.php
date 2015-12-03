<?php

namespace openacalendar\staticweb\models;

use openacalendar\staticweb\config\Config;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class Event {

    protected $id;

    protected $slug;

    protected $title;

    /** @var  /DateTime */
    protected $start;

    /** @var  /DateTime */
    protected $end;

    protected $country_id;

    /** @var  Country */
    protected $country;

    protected $timeZone;

    protected $description;

    protected $url;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



    public function setFromDataBaseRow($data) {
        $this->id = $data['id'];
        $this->slug = $data['slug'];
        $this->title = $data['summary'];
        $this->description = $data['description'];
        $utc = new \DateTimeZone("UTC");
        $this->start = new \DateTime('', $utc);
        $this->start->setTimestamp($data['start_at']);
        $this->end = new \DateTime('', $utc);
        $this->end->setTimestamp($data['end_at']);
        $this->timeZone = $data['timezone'];
        $this->url = $data['url'];
        $this->country_id = $data['country_id'];
    }



    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        if (is_string($end)) {
            $this->end = new \DateTime($end, new \DateTimeZone('UTC'));
        } else {
            $this->end = $end;
        }
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        if (is_string($start)) {
            $this->start = new \DateTime($start, new \DateTimeZone('UTC'));
        } else {
            $this->start = $start;
        }
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
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * @param mixed $country
     */
    public function setCountry(Country $country)
    {
        $this->country_id = $country->getId();
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param mixed $timeZone
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
    }


    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

}
