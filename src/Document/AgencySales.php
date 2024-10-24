<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class AgencySales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "string")]
    private $agencyId; // Référence à l'agence dans MySQL

    #[ODM\Field(type: "string")]
    private $genre; // Genre de jeu

    #[ODM\Field(type: "int")]
    private $totalCopiesSold; // Nombre total d'exemplaires vendus par cette agence

    #[ODM\Field(type: "float")]
    private $totalRevenue; // Revenu total généré par cette agence pour ce genre ou jeu

    #[ODM\Field(type: "date")]
    private $saleDate; // Date des ventes

    // Getters et setters...

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAgencyId(): ?string
    {
        return $this->agencyId;
    }

    public function setAgencyId(string $agencyId): self
    {
        $this->agencyId = $agencyId;
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