<?php

namespace Khepin\BookBundle\Security\Annotation;

/**
 * @Annotation
 */
class ValidateUser
{
    private $validation_group;

    public function __construct(array $parameters)
    {
        $this->validation_group = $parameters['value'];
    }

    public function getValidationGroup()
    {
        return $this->validation_group;
    }
}