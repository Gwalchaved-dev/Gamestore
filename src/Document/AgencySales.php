<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class AgencySales
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: "int")]
    private ?int $agencyId = null;

    #[ODM\Field(type: "date")]
    private ?\DateTimeInterface $saleDate = null;

    #[ODM\Field(type: "int")]
    private ?int $copiesSold = null;

    public function getId()
    {
        return $this->id;
    }

    public function getAgencyId(): ?int
    {
        return $this->agencyId;
    }

    public function setAgencyId(int $agencyId): self
    {
        $this->agencyId = $agencyId;
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