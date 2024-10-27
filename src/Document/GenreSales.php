<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GenreSales
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: "string")]
    private string $genre;

    #[ODM\Field(type: "date")]
    private ?\DateTimeInterface $saleDate = null;

    #[ODM\Field(type: "int")]
    private int $copiesSold = 0;

    #[ODM\Field(type: "float")]
    private float $pricePerCopy = 0.0;

    public function __construct(string $genre, \DateTimeInterface $saleDate)
    {
        $this->genre = $genre;
        $this->saleDate = $saleDate;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function getSaleDate(): \DateTimeInterface
    {
        return $this->saleDate;
    }

    public function setSaleDate(\DateTimeInterface $saleDate): self
    {
        $this->saleDate = $saleDate;
        return $this;
    }

    public function getCopiesSold(): int
    {
        return $this->copiesSold;
    }

    public function setCopiesSold(int $copiesSold): self
    {
        $this->copiesSold = $copiesSold;
        return $this;
    }

    public function getPricePerCopy(): float
    {
        return $this->pricePerCopy;
    }

    public function setPricePerCopy(float $pricePerCopy): self
    {
        $this->pricePerCopy = $pricePerCopy;
        return $this;
    }

    public function getTotalRevenue(): float
    {
        return $this->copiesSold * $this->pricePerCopy;
    }
}