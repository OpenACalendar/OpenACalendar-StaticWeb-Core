<?php

namespace openacalendar\staticweb\themes\overthewall\writecomponents;


use openacalendar\staticweb\repositories\builders\EventRepositoryBuilder;
use openacalendar\staticweb\writecomponents\BaseWriteTwigComponent;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class AllEventsICalendarComponent extends BaseWriteTwigComponent
{

    public function write()
    {

        $vCalendar = new \Eluceo\iCal\Component\Calendar('www.example.com');


        $erb = new EventRepositoryBuilder($this->siteContainer);
        // TODO up to one month old!
        foreach($erb->fetchAll() as $event) {
            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent
                ->setDtStart($event->getStart())
                ->setDtEnd($event->getEnd())
                ->setSummary($event->getTitle())
                ->setDescription($event->getDescription())
                ->setUrl($this->twigHelper->getTwig()->getExtension('openacalendar_internallink')->internalLinkToDir('/event/'. $event->getSlug()))
            ;
            $vCalendar->addComponent($vEvent);
        }

        $this->outFolder->addFileContents('api1','events.ics',$vCalendar->render());

    }

}
