<?php

namespace Khepin\BookBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Khepin\BookBundle\Security\Github\SecurityFactory;

class KhepinBookBundle extends Bundle
{
    public function build(ContainerBuilder $container)
   {
       parent::build($container);

       $extension = $container->getExtension('security');
       $extension->addSecurityListenerFactory(new SecurityFactory());
   }
}
