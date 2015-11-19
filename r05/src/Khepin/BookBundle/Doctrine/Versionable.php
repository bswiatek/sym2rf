<?php
namespace Khepin\BookBundle\Doctrine;

use Doctrine\ORM\Mapping as ORM;

Trait Versionable
{
    /**
     * @ORM\Column(name="version", type="integer", length=255)
     */
    private $version;

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }
}