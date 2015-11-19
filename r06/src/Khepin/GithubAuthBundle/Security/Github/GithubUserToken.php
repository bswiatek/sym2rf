<?php
namespace Khepin\GithubAuthBundle\Security\Github;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class GithubUserToken extends AbstractToken
{
    private $credentials;

    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }
}