<?php

namespace openacalendar\staticweb\themes\overthewall;

use openacalendar\staticweb\filters\EventFilter;
use openacalendar\staticweb\filters\GroupFilter;
use openacalendar\staticweb\OutFolder;
use openacalendar\staticweb\repositories\builders\EventRepositoryBuilder;
use openacalendar\staticweb\repositories\builders\GroupRepositoryBuilder;
use openacalendar\staticweb\Site;
use openacalendar\staticweb\themes\BaseTheme;
use openacalendar\staticweb\themes\overthewall\writecomponents\AllEventsICalendarComponent;
use openacalendar\staticweb\themes\overthewall\writecomponents\CountryWriteComponent;
use openacalendar\staticweb\themes\overthewall\writecomponents\GroupWriteComponent;
use openacalendar\staticweb\TwigHelper;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class OverTheWallTheme extends BaseTheme
{


	function write($outDir)
	{

		$twigHelper = new TwigHelper($this->siteContainer['site']);
		$twig = $twigHelper->getTwig();

		$outFolder = new OutFolder($outDir);

		// General Data
		$data = array(
			'config'=>$this->siteContainer['site']->getConfig(),
		);

        $erb = new EventRepositoryBuilder($this->siteContainer);
        $erb->setAfterNow();
        $erb->fetchAll();

		// Index
		$outFolder->addFileContents('','index.html', $twig->render('index.html.twig', array_merge($data, array(
			'events'=>$erb->fetchAll(),
		))));

        // Event pages
		$outFolder->addFileContents('event','index.html',$twig->render('eventlist/index.html.twig', array_merge($data, array(
			'events'=>$erb->fetchAll(),
		))));


        $erb = new EventRepositoryBuilder($this->siteContainer);
		$outFolder->addFileContents('event','all.html',$twig->render('eventlist/all.html.twig', array_merge($data, array(
            'events'=>$erb->fetchAll(),
		))));

        $erb = new EventRepositoryBuilder($this->siteContainer);
		foreach($erb->fetchAll() as $event) {
			$grb = new GroupRepositoryBuilder($this->siteContainer);
            $grb->setEvent($event);
			$outFolder->addFileContents('event'.DIRECTORY_SEPARATOR.$event->getSlug(),'index.html',$twig->render('event/index.html.twig', array_merge($data, array(
				'event'=>$event,
				'groups'=>$grb->fetchAll(),
			))));
		}

		// Country pages
		$x = new CountryWriteComponent($this->siteContainer, $outFolder, $twigHelper);
		$x->write();

		// Group pages
		$x = new GroupWriteComponent($this->siteContainer, $outFolder, $twigHelper);
		$x->write();

		// all ical
		$x = new AllEventsICalendarComponent($this->siteContainer, $outFolder, $twigHelper);
		$x->write();

        // CSS
        $lesscss = $this->siteContainer['lesscss'];
        $lesscss->setVariables(array(
            'colourMain'=>'#0DA20D',
            'colourDarker1'=>'#007900',
            'colourDarker2'=>'#004300',
            'colourLighter1'=>'#36C036',
            'colourLighter2'=>'#72DA72',
            'colourBackgroundOutsidePage'=>'#B7CFB7',
            'listDataItemsMarginTopBottom'=>'5px',
            'listDataItemsMarginLeftRightNarrowScreen'=>'5px',
            'listDataItemsMarginLeftRightWideScreen'=>'50px',
            'narrowScreensMakeTwoColumnsIntoOneBoundary'=>'750px',
            'narrowScreensMakeEverythingHorizontalIntoVerticalBoundary' => '500px',
        ));
        $outFolder->addFileContents(
            'css',
            'main.css',
            $lesscss->compileFile(APP_ROOT_DIR.'theme'.DIRECTORY_SEPARATOR.'overthewall'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'main.less')
        );

	}

}