<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GameSales
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: "string")]
    private string $gameId;

    #[ODM\Field(type: "date")]
    private ?\DateTimeInterface $saleDate = null;  // Initialisé à null pour éviter tout accès sans valeur

    #[ODM\Field(type: "int")]
    private int $copiesSold = 0;

    #[ODM\Field(type: "float")]
    private float $pricePerCopy = 0.0;

    #[ODM\Field(type: "string", nullable: true)]
    private ?string $genre = null;

    public function __construct(string $gameId, ?\DateTimeInterface $saleDate = null)
    {
        $this->gameId = $gameId;
        $this->saleDate = $saleDate ?? new \DateTime(); // Initialise avec la date actuelle par défaut
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }

    public function setGameId(string $gameId): self
    {
        $this->gameId = $gameId;
        return $this;
    }

    public function getSaleDate(): \DateTimeInterface
    {
        if ($this->saleDate === null) {
            $this->saleDate = new \DateTime(); // Assure une date même si elle n'a pas été initialisée
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

    // Méthode pour obtenir le revenu total
    public function getTotalRevenue(): float
    {
        return $this->copiesSold * $this->pricePerCopy;
    }
}