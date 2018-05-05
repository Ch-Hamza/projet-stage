<?php

namespace MuseumsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Critere
 *
 * @ORM\Table(name="critere")
 * @ORM\Entity(repositoryClass="MuseumsBundle\Repository\CritereRepository")
 * @Vich\Uploadable
 */
class Critere
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
    private $title_fr;

    /**
     * @ORM\Column(type="string")
     */
    private $title_en;

    /**
     * @ORM\Column(type="string")
     */
    private $title_it;

    /**
     * @ORM\Column(type="string")
     */
    private $title_de;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="critere_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

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
     * @return Critere
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
     * @return Critere
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
     * Set titleFr.
     *
     * @param string $titleFr
     *
     * @return Critere
     */
    public function setTitleFr($titleFr)
    {
        $this->title_fr = $titleFr;

        return $this;
    }

    /**
     * Get titleFr.
     *
     * @return string
     */
    public function getTitleFr()
    {
        return $this->title_fr;
    }

    /**
     * Set titleEn.
     *
     * @param string $titleEn
     *
     * @return Critere
     */
    public function setTitleEn($titleEn)
    {
        $this->title_en = $titleEn;

        return $this;
    }

    /**
     * Get titleEn.
     *
     * @return string
     */
    public function getTitleEn()
    {
        return $this->title_en;
    }

    /**
     * Set titleIt.
     *
     * @param string $titleIt
     *
     * @return Critere
     */
    public function setTitleIt($titleIt)
    {
        $this->title_it = $titleIt;

        return $this;
    }

    /**
     * Get titleIt.
     *
     * @return string
     */
    public function getTitleIt()
    {
        return $this->title_it;
    }

    /**
     * Set titleDe.
     *
     * @param string $titleDe
     *
     * @return Critere
     */
    public function setTitleDe($titleDe)
    {
        $this->title_de = $titleDe;

        return $this;
    }

    /**
     * Get titleDe.
     *
     * @return string
     */
    public function getTitleDe()
    {
        return $this->title_de;
    }
}
