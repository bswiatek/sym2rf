<?php

namespace Khepin\GithubAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Khepin\GithubAuthBundle\Security\Github\SecurityFactory;

class KhepinGithubAuthBundle extends Bundle
{
    public function build(ContainerBuilder $container)
   {
       parent::build($container);

       $extension = $container->getExtension('security');
       $extension->addSecurityListenerFactory(new SecurityFactory());
   }
}
