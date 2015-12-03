<?php

namespace openacalendar\staticweb\themes\overthewall\writecomponents;


use openacalendar\staticweb\repositories\builders\EventRepositoryBuilder;
use openacalendar\staticweb\repositories\builders\GroupRepositoryBuilder;
use openacalendar\staticweb\writecomponents\BaseWriteTwigComponent;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class GroupWriteComponent extends BaseWriteTwigComponent {


    public function write() {

        $grb = new GroupRepositoryBuilder($this->siteContainer);
        $this->outFolder->addFileContents('group','index.html', $this->twigHelper->getTwig()->render('grouplist/index.html.twig', array_merge($this->baseViewParameters, array(
            'groups'=>$grb->fetchAll(),
        ))));

        $grb = new GroupRepositoryBuilder($this->siteContainer);
        foreach($grb->fetchAll() as $group) {
            $erb = new EventRepositoryBuilder($this->siteContainer);
            $erb->setGroup($group);
            $erb->setAfterNow();

            $this->outFolder->addFileContents('group'.DIRECTORY_SEPARATOR.$group->getSlug(),'index.html',$this->twigHelper->getTwig()->render('group/index.html.twig', array_merge($this->baseViewParameters, array(
                'group'=>$group,
                'events'=>$grb->fetchAll(),
            ))));
        }

    }


}
