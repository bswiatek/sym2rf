<?php
// src/Acme/UserBundle/Entity/User.php

namespace Khepin\BookBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Khepin\BookBundle\Doctrine\NonUserOwnedEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements NonUserOwnedEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Event", mappedBy="attendees")
     */
    protected $events;

    /**
     * @ORM\Column(type="string", length=255, name="picture")
     */
    protected $picture;

    /**
     * @ORM\Column(type="string", length=255, name="phone")
     * @Assert\NotBlank(groups={"join_event"})
     */
    protected $phone;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add events
     *
     * @param \Khepin\BookBundle\Entity\Event $events
     * @return User
     */
    public function addEvent(\Khepin\BookBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Khepin\BookBundle\Entity\Event $events
     */
    public function removeEvent(\Khepin\BookBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    public function getUserId()
    {
        return 1;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($number)
    {
        $this->phone = $number;
        return $this;
    }
}