<?php
namespace Khepin\BookBundle\Doctrine;

class OwnerListener
{
    private $em;
    private $security_context;

    public function __construct($doctrine, $security_context)
    {
        $this->em = $doctrine->getManager();
        $this->security_context = $security_context;
    }

    public function updateFilter()
    {
        // $id = $this->security_context->getToken()->getUser()->getUserId();
        // $this->em->getFilters()->enable('owner_filter')->setParameter('user_id', $id);
    }
}