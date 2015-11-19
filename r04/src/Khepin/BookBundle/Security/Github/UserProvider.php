<?php

namespace Khepin\BookBundle\Security\Github;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct($user_manager)
    {
        $this->user_manager = $user_manager;
    }

    public function supportsClass($class)
    {
        return $this->user_manager->supportsClass($class);
    }

    public function loadUserByUsername($email)
    {
        $user = $this->user_manager->findUserByEmail($email);

        if (empty($user)) {
            if(empty($user)){
                $user = $this->user_manager->createUser();
                $user->setEnabled(true);
                $user->setPassword('');
                $user->setEmail($email);
                $user->setUsername($email);
            }
            $this->user_manager->updateUser($user);
        }

        if (empty($user)) {
            throw new UsernameNotFoundException('The user is not authenticated on facebook');
        }

        return $user;
    }

    public function loadOrCreateUser($email)
    {
        return $this->loadUserByUsername($email);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user)) || !$user->getEmail()) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getEmail());
    }
}