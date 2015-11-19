<?php
namespace Khepin\BookBundle\Tests\Twig;

use Khepin\BookBundle\Twig\KhepinExtension;
use Twig_Test_IntegrationTestCase;

class KhepinExtensionTest extends Twig_Test_IntegrationTestCase
{
    public function getExtensions()
    {
        return array(
            new KhepinExtension()
        );
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }
}