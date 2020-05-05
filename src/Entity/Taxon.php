<?php

namespace App\Entity;

use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
// use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxonRepository")
 * @ORM\HasLifecycleCallbacks
 * >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Vich\Uploadable
 * @UniqueEntity(
 * 	fields={"genericName","specificName"},
 * 	message="Ce taxon existe déjà !"
 * )
 */
class Taxon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $genericName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $specificName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commonName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $family;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flowering;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usedTo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vernacularNames;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $toxicity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="taxon", orphanRemoval=true)
	 * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $mainImage;

	/**
	 * 
	 */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

	/**
	 * Initialisation du slug avant le persist ..
	 * 
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 *
	 * @return void
	 */
	public function InitializeSlug()
   	{
   		// if ( empty($this->slug) ){
			   
   			// le slug est systèmatiquement recalculé ..
   			$slugify = new Slugify();
			$this->slug = $slugify->slugify($this->genericName . '-' . $this->specificName);

			// }
   	}

	/**
	 * GETTER / SETTER
	 *
	 */
	public function getId()
   	{
   		return $this->id;
   	}
	
    public function getGenericName(): ?string
    {
        return $this->genericName;
    }

    public function setGenericName(string $genericName): self
    {
        $this->genericName = $genericName;

        return $this;
    }

    public function getSpecificName(): ?string
    {
        return $this->specificName;
    }

    public function setSpecificName(string $specificName): self
    {
        $this->specificName = $specificName;

        return $this;
    }

    public function getCommonName(): ?string
    {
        return $this->commonName;
    }

    public function setCommonName(string $commonName): self
    {
        $this->commonName = $commonName;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getFlowering(): ?string
    {
        return $this->flowering;
    }

    public function setFlowering(?string $flowering): self
    {
        $this->flowering = $flowering;

        return $this;
    }

    public function getUsedTo(): ?string
    {
        return $this->usedTo;
    }

    public function setUsedTo(?string $usedTo): self
    {
        $this->usedTo = $usedTo;

        return $this;
    }

    public function getVernacularNames(): ?string
    {
        return $this->vernacularNames;
    }

    public function setVernacularNames(string $vernacularNames): self
    {
        $this->vernacularNames = $vernacularNames;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getToxicity(): ?int
    {
        return $this->toxicity;
    }

    public function setToxicity(?int $toxicity): self
    {
        $this->toxicity = $toxicity;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTaxon($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getTaxon() === $this) {
                $image->setTaxon(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

	public function getMainImage(): ?Image
   	{
   		return $this->mainImage;
   	}

	public function setMainImage(Image $image): self
   	{
   		$this->mainImage = $image;
   
   		return $this;
   	}


}

