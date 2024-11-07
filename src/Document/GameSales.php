<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GameSales
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: "string")]
    private string $gameTitre;

    #[ODM\Field(type: "date")]
    private ?\DateTimeInterface $saleDate = null;

    #[ODM\Field(type: "int")]
    private int $copiesSold = 0;

    #[ODM\Field(type: "float")]
    private float $pricePerCopy = 0.0;

    #[ODM\Field(type: "string", nullable: true)]
    private ?string $genre = null;

    public function __construct(string $gameTitre, ?\DateTimeInterface $saleDate = null)
    {
        $this->gameTitre = $gameTitre;
        $this->saleDate = $saleDate ?? new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getGameTitre(): string
    {
        return $this->gameTitre;
    }

    public function setGameTitre(string $gameTitre): self
    {
        $this->gameTitre = $gameTitre;
        return $this;
    }

    public function getSaleDate(): \DateTimeInterface
    {
        if ($this->saleDate === null) {
            $this->saleDate = new \DateTime();
        }
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

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    // Méthode calculant et renvoyant le revenu total
    public function getTotalRevenue(): float
    {
        return $this->copiesSold * $this->pricePerCopy;
    }
}