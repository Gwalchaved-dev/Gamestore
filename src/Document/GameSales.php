<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GameSales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "string")]
    private $gameName; // Nom du jeu vidéo pour référence depuis MySQL

    #[ODM\Field(type: "string")]
    private $genre; // Genre du jeu (par exemple, RPG, FPS)

    #[ODM\Field(type: "int")]
    private $copiesSold; // Nombre d'exemplaires vendus

    #[ODM\Field(type: "float")]
    private $totalRevenue; // Revenu total généré par ce jeu

    #[ODM\Field(type: "date")]
    private $saleDate; // Date de la vente

    // Getters et Setters

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getGameName(): ?string
    {
        return $this->gameName;
    }

    public function setGameName(string $gameName): self
    {
        $this->gameName = $gameName;
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

    public function getSaleDate(): ?\DateTimeInterface
    {
        return $this->saleDate;
    }

    public function setSaleDate(\DateTimeInterface $saleDate): self
    {
        $this->saleDate = $saleDate;
        return $this;
    }
}