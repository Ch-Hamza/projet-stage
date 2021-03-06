<?php

namespace MuseumsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Museum
 *
 * @ORM\Table(name="museum")
 * @ORM\Entity(repositoryClass="MuseumsBundle\Repository\MuseumRepository")
 * @Vich\Uploadable
 */

class Museum
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description_fr;

    /**
     * @ORM\Column(type="text")
     */
    private $description_en;

    /**
     * @ORM\Column(type="text")
     */
    private $description_it;

    /**
     * @ORM\Column(type="text")
     */
    private $description_de;

    /**
     * @ORM\OneToMany(targetEntity="MuseumsBundle\Entity\MuseumImage",mappedBy="museum", cascade={"persist", "remove"})
     */
    private $images;

    /**
    * @ORM\Column(type="text", nullable=true)
    */
    private $horaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="MuseumsBundle\Entity\Location", cascade={"persist","remove"})
     */
    private $location;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $place;

    /**
     * @ORM\ManyToMany(targetEntity="MuseumsBundle\Entity\Category")
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="MuseumsBundle\Entity\Canton")
     */
    private $canton;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rue;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $directions;

    /**
     * @ORM\ManyToMany(targetEntity="MuseumsBundle\Entity\Critere")
     */
    private $criteres;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true)
     * @var bool
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity="ExpositionBundle\Entity\Exposition", mappedBy="hosting_museum")
     */
    private $expositions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->criteres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name.
     *
     * @param string $name
     *
     * @return Museum
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price.
     *
     * @param string|null $price
     *
     * @return Museum
     */
    public function setPrice($price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set location.
     *
     * @param \MuseumsBundle\Entity\Location|null $location
     *
     * @return Museum
     */
    public function setLocation(\MuseumsBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location.
     *
     * @return \MuseumsBundle\Entity\Location|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Add category.
     *
     * @param \MuseumsBundle\Entity\Category $category
     *
     * @return Museum
     */
    public function addCategory(\MuseumsBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category.
     *
     * @param \MuseumsBundle\Entity\Category $category
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategory(\MuseumsBundle\Entity\Category $category)
    {
        return $this->categories->removeElement($category);
    }

    public function addImage(MuseumImage $image)
    {
        $image->setMuseum($this);
        $this->images[] = $image;
        return $this;
    }

    public function removeImage(MuseumImage $image)
    {
        return$this->images->removeElement($image);
    }

    /**
     * Get categories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set canton.
     *
     * @param \MuseumsBundle\Entity\Canton|null $canton
     *
     * @return Museum
     */
    public function setCanton(\MuseumsBundle\Entity\Canton $canton = null)
    {
        $this->canton = $canton;

        return $this;
    }

    /**
     * Get canton.
     *
     * @return \MuseumsBundle\Entity\Canton|null
     */
    public function getCanton()
    {
        return $this->canton;
    }

    /**
     * Set enabled.
     *
     * @param bool|null $enabled
     *
     * @return Museum
     */
    public function setEnabled($enabled = null)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return bool|null
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Get images.
     *
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set horaire.
     *
     * @param string|null $horaire
     *
     * @return Museum
     */
    public function setHoraire($horaire = null)
    {
        $this->horaire = $horaire;

        return $this;
    }

    /**
     * Get horaire.
     *
     * @return string|null
     */
    public function getHoraire()
    {
        return $this->horaire;
    }

    /**
     * Set place.
     *
     * @param string|null $place
     *
     * @return Museum
     */
    public function setPlace($place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place.
     *
     * @return string|null
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set directions.
     *
     * @param string|null $directions
     *
     * @return Museum
     */
    public function setDirections($directions = null)
    {
        $this->directions = $directions;

        return $this;
    }

    /**
     * Get directions.
     *
     * @return string|null
     */
    public function getDirections()
    {
        return $this->directions;
    }

    /**
     * Add critere.
     *
     * @param \MuseumsBundle\Entity\Critere $critere
     *
     * @return Museum
     */
    public function addCritere(\MuseumsBundle\Entity\Category $critere)
    {
        $this->criteres[] = $critere;

        return $this;
    }

    /**
     * Remove critere.
     *
     * @param \MuseumsBundle\Entity\Critere $critere
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCritere(\MuseumsBundle\Entity\Category $critere)
    {
        return $this->criteres->removeElement($critere);
    }

    /**
     * Get criteres.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriteres()
    {
        return $this->criteres;
    }

    /**
     * Set descriptionFr.
     *
     * @param string $descriptionFr
     *
     * @return Museum
     */
    public function setDescriptionFr($descriptionFr)
    {
        $this->description_fr = $descriptionFr;

        return $this;
    }

    /**
     * Get descriptionFr.
     *
     * @return string
     */
    public function getDescriptionFr()
    {
        return $this->description_fr;
    }

    /**
     * Set descriptionEn.
     *
     * @param string $descriptionEn
     *
     * @return Museum
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->description_en = $descriptionEn;

        return $this;
    }

    /**
     * Get descriptionEn.
     *
     * @return string
     */
    public function getDescriptionEn()
    {
        return $this->description_en;
    }

    /**
     * Set descriptionIt.
     *
     * @param string $descriptionIt
     *
     * @return Museum
     */
    public function setDescriptionIt($descriptionIt)
    {
        $this->description_it = $descriptionIt;

        return $this;
    }

    /**
     * Get descriptionIt.
     *
     * @return string
     */
    public function getDescriptionIt()
    {
        return $this->description_it;
    }

    /**
     * Set descriptionDe.
     *
     * @param string $descriptionDe
     *
     * @return Museum
     */
    public function setDescriptionDe($descriptionDe)
    {
        $this->description_de = $descriptionDe;

        return $this;
    }

    /**
     * Get descriptionDe.
     *
     * @return string
     */
    public function getDescriptionDe()
    {
        return $this->description_de;
    }

    /**
     * Set rue.
     *
     * @param string|null $rue
     *
     * @return Museum
     */
    public function setRue($rue = null)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue.
     *
     * @return string|null
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set codePostal.
     *
     * @param string|null $codePostal
     *
     * @return Museum
     */
    public function setCodePostal($codePostal = null)
    {
        $this->code_postal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal.
     *
     * @return string|null
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Museum
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fax.
     *
     * @param string|null $fax
     *
     * @return Museum
     */
    public function setFax($fax = null)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax.
     *
     * @return string|null
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return Museum
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add exposition.
     *
     * @param \ExpositionBundle\Entity\Exposition $exposition
     *
     * @return Museum
     */
    public function addExposition(\ExpositionBundle\Entity\Exposition $exposition)
    {
        $this->expositions[] = $exposition;

        return $this;
    }

    /**
     * Remove exposition.
     *
     * @param \ExpositionBundle\Entity\Exposition $exposition
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeExposition(\ExpositionBundle\Entity\Exposition $exposition)
    {
        return $this->expositions->removeElement($exposition);
    }

    /**
     * Get expositions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExpositions()
    {
        return $this->expositions;
    }

    /**
     * Set website.
     *
     * @param string|null $website
     *
     * @return Museum
     */
    public function setWebsite($website = null)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website.
     *
     * @return string|null
     */
    public function getWebsite()
    {
        return $this->website;
    }
}
