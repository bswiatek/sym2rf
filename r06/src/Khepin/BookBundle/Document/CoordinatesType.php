<?php
namespace Khepin\BookBundle\Document;

use Doctrine\ODM\MongoDB\Types\Type;
use Doctrine\ODM\MongoDB\Types\DateType;
use Khepin\BookBundle\Geo\Coordinate;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CoordinatesType extends Type
{
    public function convertToPHPValue($value)
    {
        if ($value == '') {
            return '';
        }
        return new Coordinate($value[0], $value[1]);
    }

    public function convertToDatabaseValue($value)
    {
        if (!$value instanceof Coordinate) {
            throw new UnexpectedTypeException($value, 'Khepin\BookBundle\Geo\Coordinate');
        }
        return [$value->getLatitude(), $value->getLongitude()];
    }

    public function closureToPHP()
    {
        return '$return = new \Khepin\BookBundle\Geo\Coordinate($value[0], $value[1]);';
    }
}