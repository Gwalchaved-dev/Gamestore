<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GameSales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "string")]
    private $gameId; // Référence à l'entité Game (jeu vidéo) dans MySQL

    #[ODM\Field(type: "string")]
    private $genre; // Genre du jeu (par exemple, RPG, FPS)

    #[ODM\Field(type: "int")]
    private $copiesSold; // Nombre d'exemplaires vendus

    #[ODM\Field(type: "float")]
    private $totalRevenue; // Revenu total généré par ce jeu

    #[ODM\Field(type: "date")]
    private $saleDate; // Date de la vente

    // Getters et setters...

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getGameId(): ?string
    {
        return $this->gameId;
    }

    public function setGameId(string $gameId): self
    {
        $this->gameId = $gameId;

        return $this;
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

    public function getCopiesSold(): ?int
    {
        return $this->copiesSold;
    }

    public function setCopiesSold(int $copiesSold): self
    {
        $this->copiesSold = $copiesSold;

        return $this;
    }

    public function getTotalRevenue(): ?float
    {
        return $this->totalRevenue;
    }

    public function setTotalRevenue(float $totalRevenue): self
    {
        $this->totalRevenue = $totalRevenue;

        return $this;
    }

    public function getSaleDate(): ?\DateTime
    {
        return $this->saleDate;
    }

    public function setSaleDate(\DateTime $saleDate): self
    {
        $this->saleDate = $saleDate;

        return $this;
    }
}