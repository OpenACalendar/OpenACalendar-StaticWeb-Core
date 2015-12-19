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
class AreaWriteComponent extends BaseWriteTwigComponent {


    public function write() {




        $this->outFolder->addFileContents('area','index.html', $this->twigHelper->getTwig()->render('arealist/index.html.twig', array_merge($this->baseViewParameters, array(
        ))));

        $arb = new AreaRepositoryBuilder($this->siteContainer);

        foreach($arb->fetchAll() as $area) {
            $erb = new EventRepositoryBuilder($this->siteContainer);
            $erb->setArea($area);
            $erb->setAfterNow();

            $arb = new AreaRepositoryBuilder($this->siteContainer);
            $arb->setParentArea($area);

            $this->outFolder->addFileContents('area'.DIRECTORY_SEPARATOR.$area->getSlug(),'index.html',$this->twigHelper->getTwig()->render('area/index.html.twig', array_merge($this->baseViewParameters, array(
                'country'=>$this->siteContainer['countryrepository']->loadById($area->getCountryId()),
                'area'=>$area,
                'areas'=>$arb->fetchAll(),
                'events'=>$erb->fetchAll(),
            ))));
        }

    }


}
