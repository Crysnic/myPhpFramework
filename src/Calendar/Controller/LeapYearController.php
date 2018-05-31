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
            $response = new Response('Yep, this is a leap year!');
        } else {
            $response =  new Response('Nope, this is not a leap year.'.rand());
        }

        $response->setTtl(10);

        return $response;
    }
}
