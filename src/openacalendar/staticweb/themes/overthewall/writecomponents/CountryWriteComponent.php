<?php

namespace openacalendar\staticweb\themes\overthewall\writecomponents;


use openacalendar\staticweb\repositories\builders\AreaRepositoryBuilder;
use openacalendar\staticweb\repositories\builders\CountryRepositoryBuilder;
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
class CountryWriteComponent extends BaseWriteTwigComponent {


    public function write() {



        $crbAll = new CountryRepositoryBuilder($this->siteContainer);

        $crbEvents = new CountryRepositoryBuilder($this->siteContainer);
        $crbEvents->setHasEventsOnly(true);



        $this->outFolder->addFileContents('country','index.html', $this->twigHelper->getTwig()->render('countrylist/index.html.twig', array_merge($this->baseViewParameters, array(
            'countries'=>$crbEvents->fetchAll(),
        ))));


        foreach($crbAll->fetchAll() as $country) {
            $erb = new EventRepositoryBuilder($this->siteContainer);
            $erb->setCountry($country);
            $erb->setAfterNow();

            $arb = new AreaRepositoryBuilder($this->siteContainer);
            $arb->setCountry($country);
            $arb->setNoParentArea(true);

            $this->outFolder->addFileContents('country'.DIRECTORY_SEPARATOR.strtoupper($country->getTwoCharCode()),'index.html',$this->twigHelper->getTwig()->render('country/index.html.twig', array_merge($this->baseViewParameters, array(
                'country'=>$country,
                'events'=>$erb->fetchAll(),
                'areas'=>$arb->fetchAll(),
            ))));
        }

    }


}
