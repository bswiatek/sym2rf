<?php

namespace Khepin\BookBundle\Geo;

use Ivory\GoogleMapBundle\Entity\Coordinate as GMapsCoordinate;

class Coordinate
{
    private $latitude;

    private $longitude;

    public function __construct($latitude = null, $longitude = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function __toString()
    {
        return '('.$this->latitude.', '.$this->longitude.')';
    }

    public static function createFromString($string)
    {
        var_dump($string);
        if(strlen($string) < 1){
            return new self;
        }
        $string = str_replace('(', '', $string);
        $string = str_replace(')', '', $string);
        $string = str_replace(' ', '', $string);
        $data = explode(',', $string);
        if($data[0] === "" || $data[1] === ""){
            return new self;
        }
        return new self($data[0], $data[1]);
    }

    public function toGmaps()
    {
        return new GMapsCoordinate($this->latitude, $this->longitude);
    }
}
