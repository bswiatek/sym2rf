<?php

namespace Khepin\BookBundle\Geo;



use Geocoder\Geocoder;


use Symfony\Component\HttpFoundation\Request;


class UserLocator 
{

    protected $geocoder;

    protected $user_ip;


    public function __construct(Geocoder $geocoder, Request $request) 
{
        $this->geocoder = $geocoder;
       
 $this->user_ip = $request->getClientIp();
   
     if ($this->user_ip == '127.0.0.1') 
{
            $this->user_ip = '114.247.144.250';
 
       }
    
}


    public function getUserGeoBoundaries($precision = 0.3) {
  
      // Find the user's coordinates
      
  $result = $this->geocoder->geocode($this->user_ip);
 
       $lat = $result->getLatitude();
   
     $long = $result->getLongitude();
  
      $lat_max = $lat + $precision;
// (Roughly 25km)
      
  $lat_min = $lat - $precision;
    
    $long_max = $long + $precision; // (Roughly 25km)
  
      $long_min = $long - $precision;

       
 return compact('lat_max', 'lat_min', 'long_max', 'long_min');
    }
}