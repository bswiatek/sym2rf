<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
AnnotationDriver::registerAnnotationClasses();

use Doctrine\ODM\MongoDB\Types\Type;
Type::addType('coordinates', 'Khepin\BookBundle\Document\CoordinatesType');

return $loader;