<?php

namespace openacalendar\staticweb\writecomponents;

use openacalendar\staticweb\Site;
use openacalendar\staticweb\OutFolder;
use openacalendar\staticweb\TwigHelper;
use Pimple\Container;

/**
*
* @package Core
* @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
* @license http://ican.openacalendar.org/license.html 3-clause BSD
* @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
* @author James Baster <james@jarofgreen.co.uk>
*/
abstract class BaseWriteTwigComponent extends BaseWriteComponent
{

  /** @var TwigHelper **/
  protected $twigHelper;

  protected $baseViewParameters;


  public function __construct(Container $siteContainer, OutFolder $outFolder, TwigHelper $twigHelper) {
    $this->siteContainer = $siteContainer;
    $this->outFolder = $outFolder;
    $this->twigHelper = $twigHelper;

    $this->baseViewParameters = array(
      'config'=>$this->siteContainer['site']->getConfig(),
    );
  }

}
