<?php

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;

function render_template($request): Response
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
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
    '_controller' => 'Calendar\Controller\LeapYearController::index'
]));

return $routes;