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
abstract class BaseWriteComponent
{
  /** @var  Container */
  protected  $siteContainer;


  /** @var OutFolder **/
  protected $outFolder;


  public function __construct(Container $siteContainer, OutFolder $outFolder) {
    $this->siteContainer = $siteContainer;
    $this->outFolder = $outFolder;
  }

  abstract function write();

}
