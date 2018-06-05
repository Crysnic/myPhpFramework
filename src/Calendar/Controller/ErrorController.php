<?php

declare(strict_types=1);

namespace Calendar\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ErrorController
 * @package Calendar\Controller
 */
class ErrorController
{
    /**
     * @param FlattenException $exception
     * @return string
     */
    public function exceptionAction(FlattenException $exception)
    {
        $msg = 'Something went wrong! ('.$exception->getMessage().')';

        return $msg;
    }
}
