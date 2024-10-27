<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GenreSales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "string")]
    private ?string $genre = null;

    #[ODM\Field(type: "date")]
    private ?\DateTimeInterface $saleDate = null;

    #[ODM\Field(type: "int")]
    private ?int $copiesSold = null;

    public function getId()
    {
        return $this->id;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function getSaleDate(): ?\DateTimeInterface
    {
        return $this->saleDate;
    }

    public function setSaleDate(\DateTimeInterface $saleDate): self
    {
        $this->saleDate = $saleDate;
        return $this;
    }

    public function getCopiesSold(): ?int
    {
        return $this->copiesSold;
    }

    public function setCopiesSold(int $copiesSold): self
    {
        $this->copiesSold = $copiesSold;
        return $this;
    }
}