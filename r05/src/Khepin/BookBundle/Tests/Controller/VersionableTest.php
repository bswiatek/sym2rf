<?php

namespace Khepin\BookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Khepin\BookBundle\Entity\Event;

class VersionableTest extends WebTestCase
{
    public function testVersionAdded()
    {
        $client = static::createClient();

        $meetup = new Event();
        $em = $client->getContainer()->get('doctrine')->getManager();

        $this->assertTrue($meetup->getVersion() === null);

        $em->persist($meetup);
        $em->flush();

        $em->refresh($meetup);

        $this->assertTrue($meetup->getVersion() === 1);
    }

    /**
     * @expectedException \Exception
     */
    public function testRefuseOutdated()
    {
        $client = static::createClient();

        $meetup = new Event();
        $em = $client->getContainer()->get('doctrine')->getManager();

        $em->persist($meetup);
        $em->flush();

        $em->refresh($meetup);

        $meetup->setVersion(0);
        $em->flush();
    }

    public function testIncrementedVersion()
    {
        $client = static::createClient();

        $meetup = new Event();
        $em = $client->getContainer()->get('doctrine')->getManager();

        $em->persist($meetup);
        $em->flush();

        $this->assertTrue($meetup->getVersion() === 1);

        $meetup->setName('test event');
        $meetup->setVersion(2);
        $em->flush();
        $this->assertTrue($meetup->getVersion() == 2);
    }
}