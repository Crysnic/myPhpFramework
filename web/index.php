<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Simplex\ContentLengthSubscriber;

$request = Request::createFromGlobals();
$routes = include __DIR__."/../src/app.php";

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$framework = new \Simplex\Framework(
    loadEventDispatcher(),
    $matcher,
    new HttpKernel\Controller\ControllerResolver(),
    new HttpKernel\Controller\ArgumentResolver()
);

//add caching
$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__.'/../cache')
);

$response = $framework->handle($request)->send();




function loadEventDispatcher(): EventDispatcher
{
    $dispatcher = new EventDispatcher();
    $dispatcher->addSubscriber(new ContentLengthSubscriber());
    return $dispatcher;
}