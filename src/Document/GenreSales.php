<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class GenreSales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "string")]
    private $genre; // Genre de jeu

    #[ODM\Field(type: "int")]
    private $totalCopiesSold; // Nombre total d'exemplaires vendus pour ce genre

    #[ODM\Field(type: "float")]
    private $totalRevenue; // Revenu total généré par ce genre

    #[ODM\Field(type: "date")]
    private $saleDate; // Date des ventes

    // Getters et setters...

    public function getId(): ?string
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

    public function getTotalCopiesSold(): ?int
    {
        return $this->totalCopiesSold;
    }

    public function setTotalCopiesSold(int $totalCopiesSold): self
    {
        $this->totalCopiesSold = $totalCopiesSold;
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