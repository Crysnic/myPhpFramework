<?php

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

function render_template($request): Response
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}

function is_leap_year($year = null) {
    if (null === $year) {
        $year = date('Y');
    }

    return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
}

class LeapYearController
{
    public function index($year)
    {
        if (is_leap_year($year)) {
            return new Response('Yep, this is a leap year!');
        }

        return new Response('Nope, this is not a leap year.');
    }
}


$routes = new Routing\RouteCollection();
$routes->add('hello', new Routing\Route('/myPhpFramework/hello/{name}', [
    'name' => 'World',
    '_controller' => function($request) {
        return render_template($request);
    }
]));

$routes->add('bye', new Routing\Route('/myPhpFramework/bye', ['_controller' => 'render_template']));

$routes->add('leap_year', new Routing\Route('/myPhpFramework/is_leap_year/{year}', [
    'year' => null,
    '_controller' => 'LeapYearController::index'
]));

return $routes;