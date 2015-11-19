<?php

namespace Khepin\BookBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Khepin\BookBundle\Entity\User;
use Khepin\BookBundle\Entity\Event as Meetup;

class MeetupEvent extends Event
{
    protected $user;

    protected $event;

    public function __construct(User $user, Meetup $event) {
        $this->user = $user;
        $this->event = $event;
    }

    public function getUser() {
        return $this->user;
    }

    public function getEvent() {
        return $this->event;
    }
}