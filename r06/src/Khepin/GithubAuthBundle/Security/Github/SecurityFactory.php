<?php
namespace Khepin\GithubAuthBundle\Security\Github;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class SecurityFactory extends AbstractFactory
{
    public function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'khepin.github.authentication_provider.'.$id;
        $definition = $container
            ->setDefinition($providerId, new DefinitionDecorator('khepin.github.authentication_provider'))
        ;
        if (isset($config['provider'])) {
            $definition
                ->addArgument(new Reference($userProviderId))
            ;
        }

        return $providerId;
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'github';
    }

    protected function getListenerId()
    {
        return 'khepin.github.authentication_listener';
    }
}