<?php

declare(strict_types=1);

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class Framework
 * @package Simplex
 */
class Framework
{
    /**
     * @var UrlMatcherInterface
     */
    protected $matcher;

    /**
     * @var ControllerResolverInterface
     */
    protected $controllerResolver;

    /**
     * @var ArgumentResolverInterface
     */
    protected $argumentResolver;

    /**
     * Framework constructor.
     * @param UrlMatcherInterface $matcher
     * @param ControllerResolverInterface $controllerResolver
     * @param ArgumentResolverInterface $argumentResolver
     */
    public function __construct(
        UrlMatcherInterface $matcher,
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver
    )
    {
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $this->matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not found!', 404);
        } catch (\Exception $e) {
            $response = new Response('An error occurred: '.$e->getMessage(), 500);
        }

        return $response;
    }
}
