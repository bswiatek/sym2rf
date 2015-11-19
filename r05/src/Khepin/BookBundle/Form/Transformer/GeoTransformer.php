<?php
namespace Khepin\BookBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Khepin\BookBundle\Geo\Coordinate;

class GeoTransformer implements DataTransformerInterface
{
    public function transform($geo)
    {
        return $geo;
    }

    public function reverseTransform($latlong)
    {
        return Coordinate::createFromString($latlong);
    }
}