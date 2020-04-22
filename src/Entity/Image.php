<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
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
     * @ORM\Column(type="string", length=255)
	 * @Assert\Url()
     */
    private $url;

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
     * @ORM\JoinColumn(nullable=false)
     */
    private $taxon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
		// depends on where is stored the image,
		// 		either on the web i.e. http:// or https:// 
		//		either on localhost through VichUploaderBundle

        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
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
