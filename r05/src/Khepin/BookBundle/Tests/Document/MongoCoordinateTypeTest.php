<?php

namespace Khepin\BookBundle\Tests\Document;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Khepin\BookBundle\Document\Meetup;
use Khepin\BookBundle\Geo\Coordinate;

class MongoCoordinateTypeTest extends WebTestCase
{
    public function testMapping()
    {
        $client = static::createClient();
        $dm = $client->getContainer()->get('doctrine.odm');

        $meetup = new Meetup();
        $name = uniqid();
        $meetup->setName($name);
        $meetup->setLocation(new Coordinate(33, 75));

        $dm->persist($meetup);
        $dm->flush();

        $m = new \MongoClient();
        $db = $m->extending;
        $collection = $db->Meetup;

        $met = $collection->findOne(['name' => $name]);
        $this->assertTrue(is_array($met['location']));
        $this->assertTrue($met['location'][0] === 33);

        $newName = uniqid();
        $collection->insert([
            'name' => $newName,
            'location' => [11, 22]
        ]);
        $dbmeetup = $dm->getRepository('KhepinBookBundle:Meetup')->findOneBy(['name' => $newName]);
        $this->assertTrue($dbmeetup->getLocation() instanceof Coordinate);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testTypeException()
    {
        $client = static::createClient();
        $dm = $client->getContainer()->get('doctrine.odm');

        $name = uniqid();
        $meetup = new Meetup();
        $meetup->setName($name);
        $meetup->setLocation([1,2]);

        $dm->persist($meetup);
        $dm->flush();
    }
}