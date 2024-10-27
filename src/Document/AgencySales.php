<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class AgencySales
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: "string")]
    private string $agencyId;

    #[ODM\Field(type: "date")]
    private ?\DateTimeInterface $saleDate = null;

    #[ODM\Field(type: "int")]
    private int $copiesSold = 0;

    #[ODM\Field(type: "float")]
    private float $pricePerCopy = 0.0;

    public function __construct(string $agencyId, \DateTimeInterface $saleDate)
    {
        $this->agencyId = $agencyId;
        $this->saleDate = $saleDate;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAgencyId(): string
    {
        return $this->agencyId;
    }

    public function setAgencyId(string $agencyId): self
    {
        $this->agencyId = $agencyId;
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