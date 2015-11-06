<?php


namespace openacalendar\staticweb;

use \Twig_Environment;
use \Twig_Loader_Filesystem;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class TwigHelper
{

	/** @var Twig_Environment */
	protected $twig;

	/** @var TemporaryFolder **/
	protected $cacheDir;

	function __construct(Site $site)
	{
		$this->cacheDir = new TemporaryFolder();
		$templates = array(
			APP_ROOT_DIR.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$site->getConfig()->theme.DIRECTORY_SEPARATOR.'templates',
		);
		$siteTemplates = $site->getDir().DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'templates';
		if (file_exists($siteTemplates) && is_dir($siteTemplates)) {
			array_unshift($templates, $siteTemplates);
		}
		$loader = new Twig_Loader_Filesystem($templates);
		$this->twig = new Twig_Environment($loader, array(
			'cache' => $this->cacheDir->get(),
		));
	}

	/**
	 * @return Twig_Environment
	 */
	public function getTwig()
	{
		return $this->twig;
	}

	// TODO needs cleanup function that deletes tmp files


}
