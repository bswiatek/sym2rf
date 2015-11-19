<?php

namespace Khepin\BookBundle\Event;

final class MeetupEvents
{
    /**
     * The meetup.join event is triggered every time a user registers for an
     * event.
     *
     * Listeners receive an instance of:
     * Khepin\BookBundle\Event\MeetupEvent
     */
    const MEETUP_JOIN = 'meetup.join';
}