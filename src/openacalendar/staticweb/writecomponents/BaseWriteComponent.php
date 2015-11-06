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
  protected  $app;

  /** @var Site **/
  protected $site;

  /** @var OutFolder **/
  protected $outFolder;


  public function __construct(Container $app, Site $site, OutFolder $outFolder) {
    $this->app = $app;
    $this->site = $site;
    $this->outFolder = $outFolder;
  }

  abstract function write();

}
