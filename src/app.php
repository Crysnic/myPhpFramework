<?php

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes->add('hello', new Routing\Route('/myPhpFramework/hello/{name}', ['name' => 'World']));
$routes->add('bye', new Routing\Route('/myPhpFramework/bye'));

return $routes;