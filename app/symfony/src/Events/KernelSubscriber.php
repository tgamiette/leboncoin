<?php


namespace App\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return [
            KernelEvents::VIEW => [
                ['post', ],
                ['postValidate', ],
            ]
        ];
        // TODO: Implement getSubscribedEvents() method.
    }
}