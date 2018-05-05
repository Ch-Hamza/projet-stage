<?php

namespace MuseumsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="MuseumsBundle\Repository\LocationRepository")
 * @Vich\Uploadable
 */
class Location
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $longitude;

    /**
     * @ORM\Column(type="string")
     */
    private $lattitude;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $distance;

    /**
    * @ORM\Column(type="string", nullable=true)
    */
    private $timeDistanceEstimate;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set longitude.
     *
     * @param string $longitude
     *
     * @return Location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set lattitude.
     *
     * @param string $lattitude
     *
     * @return Location
     */
    public function setLattitude($lattitude)
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    /**
     * Get lattitude.
     *
     * @return string
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }

    /**
     * Set distance.
     *
     * @param string $distance
     *
     * @return Location
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance.
     *
     * @return string
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set timeDistanceEstimate.
     *
     * @param string $timeDistanceEstimate
     *
     * @return Location
     */
    public function setTimeDistanceEstimate($timeDistanceEstimate)
    {
        $this->timeDistanceEstimate = $timeDistanceEstimate;

        return $this;
    }

    /**
     * Get timeDistanceEstimate.
     *
     * @return string
     */
    public function getTimeDistanceEstimate()
    {
        return $this->timeDistanceEstimate;
    }
}
