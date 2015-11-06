<?php

namespace openacalendar\staticweb\writecomponents;

use openacalendar\staticweb\Site;
use openacalendar\staticweb\OutFolder;
use openacalendar\staticweb\TwigHelper;
use openacalendar\staticweb\filters\EventFilter;


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

    $this->outFolder->addFileContents('group','index.html', $this->twigHelper->getTwig()->render('grouplist/index.html.twig', array_merge($this->baseViewParameters, array(
    ))));

    foreach($this->site->getGroups() as $group) {
      $groupFilter = new EventFilter($this->site, $this->app);
      $groupFilter->setGroup($group);
      $groupFilter->setPresentOrFutureOnly(true);

      $this->outFolder->addFileContents('group'.DIRECTORY_SEPARATOR.$group->getSlug(),'index.html',$this->twigHelper->getTwig()->render('group/index.html.twig', array_merge($this->baseViewParameters, array(
        'group'=>$group,
        'events'=>$groupFilter->get(),
      ))));
    }

  }


}
