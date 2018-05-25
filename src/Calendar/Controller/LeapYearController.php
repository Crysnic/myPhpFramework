<?php

declare(strict_types=1);

namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LeapYearController
 * @package Calendar\Controller
 */
class LeapYearController
{
    /**
     * @param $year
     * @return Response
     */
    public function index($year)
    {
        $leapYear = new LeapYear();
        if ($leapYear->isLeapYear($year)) {
            return new Response('Yep, this is a leap year!');
        }

        return new Response('Nope, this is not a leap year.');
    }
}
