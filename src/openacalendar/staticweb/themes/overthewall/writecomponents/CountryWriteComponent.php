<?php

namespace openacalendar\staticweb\themes\overthewall\writecomponents;


use openacalendar\staticweb\aggregation\EventDistinctValuesAggregation;
use openacalendar\staticweb\filters\EventFilter;
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


		$eventPresentOrFutureFilter = new EventFilter($this->site, $this->app);
		$eventPresentOrFutureFilter->setPresentOrFutureOnly(true);
		$eventPresentOrFutureAggregation = new EventDistinctValuesAggregation($eventPresentOrFutureFilter);

		$this->outFolder->addFileContents('country','index.html', $this->twigHelper->getTwig()->render('countrylist/index.html.twig', array_merge($this->baseViewParameters, array(
			'countries'=>$eventPresentOrFutureAggregation->getDistinctCountries(),
		))));

		$evenAllFilter = new EventFilter($this->site, $this->app);
		$eventAllAggregation = new EventDistinctValuesAggregation($evenAllFilter);
		foreach($eventAllAggregation->getDistinctCountries() as $country) {
			$groupFilter = new EventFilter($this->site, $this->app);
			$groupFilter->setCountry($country);
			$groupFilter->setPresentOrFutureOnly(true);

			$this->outFolder->addFileContents('country'.DIRECTORY_SEPARATOR.strtoupper($country->getCode()),'index.html',$this->twigHelper->getTwig()->render('country/index.html.twig', array_merge($this->baseViewParameters, array(
				'country'=>$country,
				'events'=>$groupFilter->get(),
			))));
		}

	}


}
