<?php

namespace Khepin\BookBundle\Tests\Geo;

use Khepin\BookBundle\Geo\UserLocator;
use PHPUnit_Framework_TestCase;

class UserLocatorTest extends PHPUnit_Framework_TestCase
{
    public function testGetBoundaries()
    {
        $geocoder = $this->getMock('Geocoder\Geocoder');
        $result = $this->getMock('Geocoder\Result\Geocoded');

        $geocoder->expects($this->any())->method('geocode')->will($this->returnValue($result));
        $result->expects($this->any())
            ->method('getLatitude')->will($this->returnValue(3));
        $result->expects($this->any())
            ->method('getLongitude')->will($this->returnValue(7));

        $request = $this->getMock('Symfony\Component\HttpFoundation\Request', ['getUserIp']);
        $locator = new UserLocator($geocoder, $request);

        $boundaries = $locator->getUserGeoBoundaries(0);

        $this->assertTrue($boundaries['lat_min'] == 3);
        $this->assertTrue($boundaries['lat_max'] == 3);
        $this->assertTrue($boundaries['long_max'] == 7);
        $this->assertTrue($boundaries['long_min'] == 7);
    }
}