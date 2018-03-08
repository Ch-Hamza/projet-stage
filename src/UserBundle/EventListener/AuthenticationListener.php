<?php

namespace UserBundle\EventListener;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\EventListener\AuthenticationListener as FOSAuthenticationListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthenticationListener extends FOSAuthenticationListener
{

    public function authenticate(FilterUserResponseEvent $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'authenticate',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'authenticate'
        );
    }

}