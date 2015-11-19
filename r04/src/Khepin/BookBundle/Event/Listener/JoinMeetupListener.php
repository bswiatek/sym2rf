<?php

namespace Khepin\BookBundle\Event\Listener;

use Khepin\BookBundle\Event\MeetupEvent;
use Khepin\BookBundle\Event\MeetupEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class JoinMeetupListener implements EventSubscriberInterface
{
    protected $event;

    public static function getSubscribedEvents() {
        return [
            MeetupEvents::MEETUP_JOIN   => 'onUserJoinsMeetup',
            KernelEvents::TERMINATE     => 'generatePreferences'
        ];
    }

    public function onUserJoinsMeetup(MeetupEvent $event) {
        $this->event = $event;
    }

    public function generatePreferences() {
        if ($this->event) {
            // Generate the new preferences for the user
        }
    }
}







// use Symfony\Bridge\Monolog\Logger;

    // public function __construct(Logger $logger) {
    //     $this->logger = $logger;
    // }
