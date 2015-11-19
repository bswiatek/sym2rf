<?php

namespace Khepin\BookBundle\Security\Voters;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CountryVoter implements VoterInterface
{
    protected $country_code;

    public function __construct($service_container)
    {
        $this->country_code = $service_container->get('user_locator')->getCountryCode();
    }

    public function supportsAttribute($attribute)
    {
        return $attribute === 'MEETUP_CREATE';
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object)) || !$this->supportsAttribute($attributes[0])) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        if ($this->country_code === 'CN') {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_DENIED;
    }
}