<?php

namespace Khepin\GithubAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KhepinGithubAuthExtension extends Extension
{
    private $namespace = 'khepin_github_auth';
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->setParameters($container, $config, $this->namespace);
    }

    /**
     * Adds configuration parameters as container parameters
     * @param ContainerBuilder $container
     * @param array            $config
     * @param [type]           $namespace
     */
    public function setParameters(ContainerBuilder $container, array $config, $namespace)
    {
        foreach ($config as $key => $value) {
            $container->setParameter($namespace . '.' . $key, $value);
        }
    }
}
