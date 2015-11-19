<?php
namespace Khepin\BookBundle\Security\Github;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Khepin\BookBundle\Security\Github\GithubUserToken;

class AuthenticationProvider implements AuthenticationProviderInterface
{
    private $user_provider;

    public function __construct($user_provider)
    {
        $this->user_provider = $user_provider;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof GithubUserToken;
    }

    public function authenticate(TokenInterface $token)
    {
        $email = $token->getCredentials();
        $user = $this->user_provider->loadOrCreateUser($email);
        // Log the user in
        $new_token = new GithubUserToken($user->getRoles());
        $new_token->setUser($user);
        $new_token->setAuthenticated(true);
        return $new_token;
    }
}