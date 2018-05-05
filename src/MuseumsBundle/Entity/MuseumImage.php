<?php

namespace MuseumsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * MuseumImage
 *
 * @ORM\Table(name="museum_image")
 * @ORM\Entity(repositoryClass="MuseumsBundle\Repository\MuseumImageRepository")
 * @Vich\Uploadable
 */
class MuseumImage
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
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="museum_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="MuseumsBundle\Entity\Museum", inversedBy="images")
     * @ORM\JoinColumn(name="museum_id", referencedColumnName="id")
     */
    private $museum;

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
     * Set image.
     *
     * @param string $image
     *
     * @return MuseumImage
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return MuseumImage
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Set museum.
     *
     * @param \MuseumsBundle\Entity\Museum|null $museum
     *
     * @return MuseumImage
     */
    public function setMuseum(\MuseumsBundle\Entity\Museum $museum = null)
    {
        $this->museum = $museum;

        return $this;
    }

    /**
     * Get museum.
     *
     * @return \MuseumsBundle\Entity\Museum|null
     */
    public function getMuseum()
    {
        return $this->museum;
    }
}
