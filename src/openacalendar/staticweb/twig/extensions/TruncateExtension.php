<?php

namespace openacalendar\staticweb\twig\extensions;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2015, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class TruncateExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array();
    }

    public function getFilters()
    {
        return array(
            'truncate' => new \Twig_Filter_Method($this, 'truncate'),
        );
    }

    public function truncate($data, $width=75)
    {
		if (mb_strlen($data) > $width) {
			$pos = $width;
			while(!in_array(mb_substr($data, $pos, 1),array(" ","\n","\r","\t")) && $pos < mb_strlen($data)) {
				$pos++;
			}
			if ($pos < mb_strlen($data)) {
				return mb_substr($data, 0, $pos)." ...";
			} else {
				return $data;
			}
		} else {
			return $data;
		}
    }

    public function getName()
    {
        return 'openacalendar_truncate';
    }
}

