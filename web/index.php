<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;

$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes = include __DIR__."/../src/app.php";

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$framework = new \Simplex\Framework(
    loadEventDispatcher($matcher, $requestStack),
    new HttpKernel\Controller\ControllerResolver(),
    $requestStack,
    new HttpKernel\Controller\ArgumentResolver()
);

//add caching
$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__.'/../cache'),
    new HttpKernel\HttpCache\Esi(),
    ['debug' => true]
);

$response = $framework->handle($request)->send();




function loadEventDispatcher(Routing\Matcher\UrlMatcherInterface $matcher, RequestStack $requestStack): EventDispatcher
{
    $exceptionListener = new HttpKernel\EventListener\ExceptionListener(
        'Calendar\Controller\ErrorController::exceptionAction'
    );

    $dispatcher = new EventDispatcher();
    $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
    $dispatcher->addSubscriber($exceptionListener);
    $dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
    $dispatcher->addSubscriber(new HttpKernel\EventListener\StreamedResponseListener());
    $dispatcher->addSubscriber(new \Simplex\StringResponseSubscriber());
    return $dispatcher;
}