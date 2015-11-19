<?php

namespace Khepin\BookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        // $this->assertTrue($crawler->filter('html:contains("Concert of the Funk U")')->count() > 0);
        // $this->assertTrue($crawler->filter('html:contains("Beijing GuoAn vs. Real Madrid")')->count() == 0);
    }

    public function testIndexMock()
    {
        $client = static::createClient();

        $locator = $this->getMockBuilder('Khepin\BookBundle\Geo\UserLocator')
                     ->disableOriginalConstructor()
                     ->getMock();
        $boundaries = ["lat_max" => 40.2289, "lat_min" => 39.6289, "long_max" => 116.6883, "long_min" => 116.0883];
        $locator->expects($this->any())
            ->method('getUserGeoBoundaries')->will($this->returnValue($boundaries));

        $client->getContainer()->set('user_locator', $locator);

        $crawler = $client->request('GET', '/');

        // $this->assertTrue($crawler->filter('html:contains("Concert of the Funk U")')->count() > 0);
        // $this->assertTrue($crawler->filter('html:contains("Beijing GuoAn vs. Real Madrid")')->count() == 0);
    }
}