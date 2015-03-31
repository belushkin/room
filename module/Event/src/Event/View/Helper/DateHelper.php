<?php

namespace Event\View\Helper;

use Zend\View\Helper\AbstractHelper;

class DateHelper extends AbstractHelper
{

    public function __invoke($str)
    {
        $result = '';
        if (date('Y-m-d', $str) == date('Y-m-d', strtotime("today"))) {
            $result = "Today, ";
        } else if (date('Y-m-d', $str) == date('Y-m-d', strtotime("yesterday"))) {
            $result ="Yesterday, ";
        }
        return $result . date('F jS Y', $str);
    }

}
