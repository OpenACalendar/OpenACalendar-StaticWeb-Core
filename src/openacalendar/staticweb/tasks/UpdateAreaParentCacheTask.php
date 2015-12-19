<?php


namespace openacalendar\staticweb\tasks;

use openacalendar\staticweb\repositories\builders\AreaRepositoryBuilder;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class UpdateAreaParentCacheTask extends BaseTask {


    function run()
    {

        $arb = new AreaRepositoryBuilder($this->siteContainer);
        foreach($arb->fetchAll() as $area) {
            $this->siteContainer['arearepository']->buildCacheAreaHasParent($area);
        }

    }
}
