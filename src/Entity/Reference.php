<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReferenceRepository")
 */
class Reference
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bookTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webRef;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Taxon", inversedBy="reference")
     * @ORM\JoinColumn(nullable=false)
     */
    private $taxon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getBookTitle(): ?string
    {
        return $this->bookTitle;
    }

    public function setBookTitle(?string $bookTitle): self
    {
        $this->bookTitle = $bookTitle;

        return $this;
    }

    public function getWebRef(): ?string
    {
        return $this->webRef;
    }

    public function setWebRef(?string $webRef): self
    {
        $this->webRef = $webRef;

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
