<?php

namespace Khepin\BookBundle\Security\Voters;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MeetupVoter implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return $attribute === 'EDIT';
    }

    public function supportsClass($class)
    {
        return $class === 'Khepin\BookBundle\Entity\Event';
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object)) || !$this->supportsAttribute($attributes[0])) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (
            $this->meetupHasNoAttendees($object)
            && $this->isMeetupCreator($token->getUser(), $object)
        ) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }

    protected function meetupHasNoAttendees($meetup)
    {
        return $meetup->getAttendees()->count() === 0;
    }

    protected function isMeetupCreator($user, $meetup)
    {
        return $user->getUserId() === $meetup->getUserId();
    }
}