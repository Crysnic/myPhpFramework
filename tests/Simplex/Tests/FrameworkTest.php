<?php

declare(strict_types=1);

namespace Simplex\Tests;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class FrameworkTest extends TestCase
{
    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testErrorHandling()
    {
        $framework = $this->getFrameworkForException(new \RuntimeException());

        $response = $framework->handle(new Request());

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testControllerResponse()
    {
        $matcher = $this->createMock(UrlMatcherInterface::class);

        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->returnValue([
                '_route' => 'foo',
                'name' => 'Fabien',
                '_controller' => function($name) {
                    return new Response('Hello '.$name);
                }
            ]))
        ;

        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock(RequestContext::class)))
        ;
        $eventDispatcher = new EventDispatcher();
        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $framework = new Framework($eventDispatcher, $matcher, $controllerResolver, $argumentResolver);

        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello Fabien', $response->getContent());
    }

    private function getFrameworkForException($exception)
    {
        $matcher = $this->createMock(UrlMatcherInterface::class);

        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;

        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock(RequestContext::class)))
        ;
        $eventDispatcher = $this->createMock(EventDispatcher::class);
        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($eventDispatcher, $matcher, $controllerResolver, $argumentResolver);
    }
}
