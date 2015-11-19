<?php

namespace Khepin\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Event
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    protected $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    protected $longitude;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="events")
     */
    protected $attendees;

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
     * Set name
     *
     * @param string $name
     * @return Event
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Event
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Event
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attendees = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add attendees
     *
     * @param \Khepin\BookBundle\Entity\User $attendees
     * @return Event
     */
    public function addAttendee(\Khepin\BookBundle\Entity\User $attendees)
    {
        $this->attendees[] = $attendees;

        return $this;
    }

    /**
     * Remove attendees
     *
     * @param \Khepin\BookBundle\Entity\User $attendees
     */
    public function removeAttendee(\Khepin\BookBundle\Entity\User $attendees)
    {
        $this->attendees->removeElement($attendees);
    }

    /**
     * Get attendees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttendees()
    {
        return $this->attendees;
    }
}