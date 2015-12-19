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
class AreaModel
{

    protected $id;

    protected $slug;

    protected $title;

    protected $country_id;

    protected $parent_area_id;

    public function setFromDataBaseRow($data) {
        $this->id = $data['id'];
        $this->slug = $data['slug'];
        $this->title = $data['title'];
        $this->country_id = $data['country_id'];
        $this->parent_area_id = $data['parent_area_id'];
    }

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
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * @param mixed $country
     */
    public function setCountry(CountryModel $country)
    {
        $this->country_id = $country->getId();
    }

    /**
     * @return mixed
     */
    public function getParentAreaId()
    {
        return $this->parent_area_id;
    }

    /**
     * @param mixed $parent_area_id
     */
    public function setParentAreaId($parent_area_id)
    {
        $this->parent_area_id = $parent_area_id;
    }

    /**
     */
    public function setParentArea(AreaModel $parent_area)
    {
        $this->parent_area_id = $parent_area->getId();
    }


    public function getDataForLoggerInfo() {
        return array(
            'slug' => $this->slug,
            'title' => $this->title,
        );
    }

}
