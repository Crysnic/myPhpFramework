<?php

declare(strict_types=1);

namespace Simplex;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ContentLengthListener
 * @package Simplex
 */
class ContentLengthSubscriber implements EventSubscriberInterface
{
    /**
     * @param ResponseEvent $event
     */
    public function onResponse(ResponseEvent $event)
    {
        $headers = $event->getResponse()->headers;

        if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
            $headers->set('Content-Length', strlen($event->getResponse()->getContent()));
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return ['response' => ['onResponse', -255]];
    }
}
