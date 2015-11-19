<?php

namespace Khepin\BookBundle\Doctrine;
use Doctrine\ORM\Mapping\ClassMetaData,
    Doctrine\ORM\Query\Filter\SQLFilter;

class OwnerFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->reflClass->implementsInterface('Khepin\BookBundle\Doctrine\NonUserOwnedEntity')) {
            return "";
        }
        return $targetTableAlias.'.user_id = ' . $this->getParameter('user_id');
    }
}