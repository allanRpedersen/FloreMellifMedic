<?php

namespace App\Entity;

use App\Entity\Taxon;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @Vich\Uploadable
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Assert\Url(
	 * 		relativeProtocol = true
	 * )
     */
	private $url;
	
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Assert\Url(
	 * 		relativeProtocol = true
	 * )
     */
	private $uploadedImageName;
	
	/**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="taxon_image", fileNameProperty="uploadedImageName", size="uploadedImageSize")
     * 
     * @var File|null
     */
	private $uploadedImageFile;

	/**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $uploadedImageSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;


    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\Length(
	 * 	min=7,
	 * 	minMessage="Le titre de l'illustration doit faire au moins 7 caractères !"
	 * )
     */
    private $caption;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Taxon", inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
	private $taxon;

	//
	//
	//

	/**
	 * 
	 *
	 */
	public function __construct()
	{
	}

	
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
		//
		$this->updatedAt = new \DateTimeImmutable();
		//
		
		$this->url = $url;
        return $this;
    }
	
	public function getUploadedImageName(): ?string
	{
		return $this->uploadedImageName;
	}

	public function setUploadedImageName(?string $uploadedImageName ): self
	{
		$this->uploadedImageName = $uploadedImageName;
		return $this;
	}

    public function setUploadedImageSize(?int $uploadedImageSize): self
    {
		$this->uploadedImageSize = $uploadedImageSize;
		
		return $this;
    }

    public function getUploadedImageSize(): ?int
    {
        return $this->uploadedImageSize;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $uploadedImageFile
     */
    public function setUploadedImageFile(?File $uploadedImageFile = null): void
    {
        $this->uploadedImageFile = $uploadedImageFile;

        if (null !== $uploadedImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
		}

	}

    public function getUploadedImageFile(): ?File
    {
        return $this->uploadedImageFile;
	}
	
    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getTaxon(): ?Taxon
    {
        return $this->taxon;
    }

    public function setTaxon(?Taxon $taxon): self
    {
        $this->taxon = $taxon;

        return $this;
    }
}
